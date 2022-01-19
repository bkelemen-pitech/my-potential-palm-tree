<?php

namespace App\Service;

use App\Enum\FolderEnum;
use App\Exception\ResourceNotFoundException;
use App\Model\Request\BaseFolderFiltersModel;
use Kyc\InternalApiBundle\Exception\ResourceNotFoundException as KycResourceNotFoundException;
use Kyc\InternalApiBundle\Exception\InvalidDataException as InternalAPIInvalidDataException;
use Kyc\InternalApiBundle\Model\Request\Administrator\AssignedAdministratorFilterModel;
use Kyc\InternalApiBundle\Model\Request\Folder\UpdateStatusWorkflowModel;
use Kyc\InternalApiBundle\Model\Response\Folder\AssignedAdministratorModelResponse;
use Kyc\InternalApiBundle\Model\Response\Folder\FolderByIdModelResponse;
use Kyc\InternalApiBundle\Model\Response\Folder\FolderModelResponse;
use Kyc\InternalApiBundle\Service\FolderService as InternalApiFolderService;
use Kyc\InternalApiBundle\Service\DocumentService as InternalApiDocumentService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class FolderService
{
    protected SerializerInterface $serializer;
    protected ValidationService $validationService;
    protected InternalApiFolderService $internalApiFolderService;
    protected InternalApiDocumentService $internalApiDocumentService;
    protected LoggerInterface $logger;

    public function __construct(
        SerializerInterface $serializer,
        ValidationService $validationService,
        InternalApiFolderService $internalApiFolderService,
        InternalApiDocumentService $internalApiDocumentService,
        LoggerInterface $logger
    ) {
        $this->serializer = $serializer;
        $this->validationService = $validationService;
        $this->internalApiFolderService = $internalApiFolderService;
        $this->internalApiDocumentService = $internalApiDocumentService;
        $this->logger = $logger;
    }

    public function getFolders(array $data): array
    {
        $folderFiltersModel = $this->serializer->deserialize(
            \json_encode($data),
            BaseFolderFiltersModel::class,
            'json'
        );

        $data = $this->internalApiFolderService->getFolders($folderFiltersModel);

        $folderIds = [];
        foreach ($data[FolderEnum::FOLDERS] as $folder) {
            $folderIds[] = $folder->getFolderId();
        }

        $assignedAdministrators = $this->getAssignedAdministratorsByFolderIds($folderIds);

        $folders = $this->assignAdministratorsToFolders($assignedAdministrators, $data[FolderEnum::FOLDERS]);

        return [
            FolderEnum::FOLDERS => $folders,
            FolderEnum::META => $data[FolderEnum::META],
        ];
    }

    public function getDocuments(int $folderId): array
    {
        return $this->internalApiDocumentService->getDocumentsByFolderId($folderId);
    }

    public function getFolderData(int $folderId, array $filters): FolderByIdModelResponse
    {
        try {
            $folderById = $this->internalApiFolderService->getFolderById($folderId);
            $persons = $this->internalApiFolderService->getPersonsByFolderId($folderId, $filters);
            $folderById->setPersons($persons);

            if ($folderById->getWorkflowStatus() == FolderEnum::WORKFLOW_STATUS_PROCESSED_BY_WEBHELP) {
                try {
                    // add administrator ID when login is done.
                    $updateStatusWorkflowModel = new UpdateStatusWorkflowModel();
                    $updateStatusWorkflowModel
                        ->setUserDossierId($folderId)
                        ->setStatusWorkflow(FolderEnum::WORKFLOW_STATUS_IN_PROGRESS_BY_WEBHELP)
                    ;

                    $this->internalApiFolderService->updateStatusWorkflow(
                        $updateStatusWorkflowModel
                    );

                    $folderById->setWorkflowStatus(FolderEnum::WORKFLOW_STATUS_IN_PROGRESS_BY_WEBHELP);
                } catch (InternalAPIInvalidDataException $exception) {
                    $this->logger->error($exception->getMessage(), [$exception->getTrace()]);
                }
            }

            return $folderById;
        } catch (KycResourceNotFoundException $exception) {
            throw new ResourceNotFoundException($exception->getMessage());
        }
    }

    /**
     * @return AssignedAdministratorModelResponse[]
     */
    public function getAssignedAdministratorsByFolderIds(array $folderIds): array
    {
        $filterModel = new AssignedAdministratorFilterModel();
        $filterModel->setUserDossierIds(\array_filter(\array_unique($folderIds)));

        return $this->internalApiFolderService->getAssignedAdministrators($filterModel);
    }

    /**
     * @param AssignedAdministratorModelResponse[]
     * @param FolderModelResponse[]
     *
     * @return FolderByIdModelResponse[]
     */
    public function assignAdministratorsToFolders(array $assignedAdministrators, array $folderModelResponses): array
    {
        $hashedAssignedAdministrators = [];
        foreach ($assignedAdministrators as $assignedAdministrator) {
            $hashedAssignedAdministrators[$assignedAdministrator->getFolderId()] = $assignedAdministrator;
        }

        foreach ($folderModelResponses as $folder) {
            if (!empty($hashedAssignedAdministrators[$folder->getFolderId()])) {
                $folder->setAssignedTo($hashedAssignedAdministrators[$folder->getFolderId()]->getUsername());
            }
        }

        return $folderModelResponses;
    }
}
