<?php

namespace App\Service;

use App\Enum\FolderEnum;
use App\Enum\PersonEnum;
use App\Enum\UserEnum;
use App\Exception\InvalidDataException;
use App\Exception\ResourceNotFoundException;
use App\Model\Request\BaseFolderFiltersModel;
use App\Security\JWTTokenAuthenticator;
use Kyc\InternalApiBundle\Enum\FolderEnum as InternalApiFolderEnum;
use Kyc\InternalApiBundle\Exception\ResourceNotFoundException as KycResourceNotFoundException;
use Kyc\InternalApiBundle\Exception\InvalidDataException as InternalAPIInvalidDataException;
use Kyc\InternalApiBundle\Model\Request\Administrator\AssignedAdministratorFilterModel;
use Kyc\InternalApiBundle\Model\Request\Folder\DissociateFolderModel;
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
    protected JWTTokenAuthenticator $authenticator;

    public function __construct(
        SerializerInterface $serializer,
        ValidationService $validationService,
        InternalApiFolderService $internalApiFolderService,
        InternalApiDocumentService $internalApiDocumentService,
        LoggerInterface $logger,
        JWTTokenAuthenticator $authenticator
    ) {
        $this->serializer = $serializer;
        $this->validationService = $validationService;
        $this->internalApiFolderService = $internalApiFolderService;
        $this->internalApiDocumentService = $internalApiDocumentService;
        $this->logger = $logger;
        $this->authenticator = $authenticator;
    }

    public function getFolders(array $data): array
    {
        $data = $this->setExtraFilters($data);
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
                    $administratorId = $this->authenticator->getLoggedUserData()[UserEnum::USER_ID];

                    $this->internalApiFolderService->assignAdministratorToFolder($administratorId, $folderId);

                    $this->updateWorkflowStatus(
                        $folderById->getId(),
                        [InternalApiFolderEnum::WORKFLOW_STATUS_CAMEL_CASE => FolderEnum::WORKFLOW_STATUS_IN_PROGRESS_BY_WEBHELP],
                        $administratorId
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

    public function updateWorkflowStatus(int $folderId, array $data, ?int $administratorId = null): void
    {
        $administratorId = $administratorId ?: $this->authenticator->getLoggedUserData()[UserEnum::USER_ID];

        $updateStatusWorkflowModel = new UpdateStatusWorkflowModel();
        $updateStatusWorkflowModel
            ->setUserDossierId($folderId)
            ->setAdministratorId($administratorId)
            ->setStatusWorkflow($data[InternalApiFolderEnum::WORKFLOW_STATUS_CAMEL_CASE] ?? null)
            ->setAllowBack($data[InternalApiFolderEnum::ALLOW_BACK_DB_CAMEL_CASE] ?? null);

        $this->internalApiFolderService->updateStatusWorkflow($updateStatusWorkflowModel);
    }

    public function dissociateFolder(int $folderId)
    {
        $folder = $this->internalApiFolderService->getFolderById($folderId);
        if ($folder->getWorkflowStatus() <= FolderEnum::WORKFLOW_STATUS_PROCESSED_BY_WEBHELP) {
            throw new InvalidDataException('The folder cannot be dissociated.');
        }
        $dissociateFolderModel = new DissociateFolderModel();
        $dissociateFolderModel->setFolderId($folderId);

        try {
            $this->updateWorkflowStatus(
                $folderId,
                [
                    InternalApiFolderEnum::WORKFLOW_STATUS_CAMEL_CASE => FolderEnum::WORKFLOW_STATUS_PROCESSED_BY_WEBHELP,
                    InternalApiFolderEnum::ALLOW_BACK_DB_CAMEL_CASE => true,
                ]
            );
        } catch (\Exception $exception) {
            throw new InvalidDataException('The folder cannot be dissociated because of its workflow status.');
        }
        $this->internalApiFolderService->dissociateFolder($dissociateFolderModel);
    }

    private function setExtraFilters(array $data): array
    {
        if (isset($data[InternalApiFolderEnum::TEXT_SEARCH_FIELDS])) {
            $textSearchFields = explode(',', $data[InternalApiFolderEnum::TEXT_SEARCH_FIELDS]);
            if (in_array(InternalApiFolderEnum::PERSON_DATE_OF_BIRTH, $textSearchFields)) {
                if (isset($data[InternalApiFolderEnum::FILTERS])) {
                    $data[InternalApiFolderEnum::FILTERS] .= sprintf(
                        ',%s:%s',
                        InternalApiFolderEnum::PERSON_TYPE_ID,
                        PersonEnum::MAIN_PHYSICAL_PERSON_TYPE_ID
                    );
                } else {
                    $data[InternalApiFolderEnum::FILTERS] = sprintf(
                        '%s:%s',
                        InternalApiFolderEnum::PERSON_TYPE_ID,
                        PersonEnum::MAIN_PHYSICAL_PERSON_TYPE_ID
                    );
                }
            }
        }

        return $data;
    }
}
