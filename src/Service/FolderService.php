<?php

namespace App\Service;

use App\Enum\AdministratorEnum;
use App\Enum\FolderEnum;
use App\Enum\PersonEnum;
use App\Enum\UserEnum;
use App\Exception\ResourceNotFoundException;
use App\Model\Request\BaseFolderFiltersModel;
use App\Security\JWTTokenAuthenticator;
use Kyc\InternalApiBundle\Enum\FolderEnum as InternalApiFolderEnum;
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
        $view = isset($data[FolderEnum::VIEW]) ? (int) $data[FolderEnum::VIEW] : null;
        $viewCriteria = isset($data[FolderEnum::VIEW_CRITERIA]) ? (int) $data[FolderEnum::VIEW_CRITERIA] : null;
        $userId = $view === FolderEnum::VIEW_IN_TREATMENT ? $this->getUserId($data, $viewCriteria) : null;

        $data = $this->handleQueryParameters($data, $userId);
        $data = $this->setExtraFilters($data);
        $folderFiltersModel = $this->serializer->deserialize(
            \json_encode($data),
            BaseFolderFiltersModel::class,
            'json'
        );

        $foldersResponse = $this->internalApiFolderService->getFolders($folderFiltersModel);
        [$folders, $total] = $this->assignAdministratorsToFoldersIfNeeded(
            $foldersResponse,
            $data,
            $userId,
            $viewCriteria
        );

        return [
            FolderEnum::FOLDERS => $folders,
            FolderEnum::META => [
                FolderEnum::TOTAL => $total,
                FolderEnum::VIEW_CRITERIA => $this->getReturnedViewCriteria($folders, $viewCriteria),
            ],
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

        /** @var FolderModelResponse $folder */
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
            ->setStatusWorkflow($data[InternalApiFolderEnum::WORKFLOW_STATUS_CAMEL_CASE] ?? null);

        $this->internalApiFolderService->updateStatusWorkflow($updateStatusWorkflowModel);
    }

    private function assignAdministratorsToFoldersIfNeeded(
        array $foldersResult,
        array $queryParameters,
        ?int $userId,
        ?int $viewCriteria
    ) {
        $folders = $foldersResult[FolderEnum::FOLDERS];

        if (is_null($userId) || (is_null($viewCriteria) && empty($folders))) {
            if (is_null($viewCriteria) && empty($folders)) {
                unset($queryParameters[AdministratorEnum::ADMINISTRATOR_ID_CAMEL_CASE]);
                $folderFiltersModelWithoutAdministratorId = $this->serializer->deserialize(
                    \json_encode($queryParameters),
                    BaseFolderFiltersModel::class,
                    'json'
                );
                $foldersResult = $this->internalApiFolderService->getFolders($folderFiltersModelWithoutAdministratorId);
            }
            $folderIds = [];

            foreach ($foldersResult[FolderEnum::FOLDERS] as $folder) {
                $folderIds[] = $folder->getFolderId();
            }

            $assignedAdministrators = $this->getAssignedAdministratorsByFolderIds($folderIds);
            $folders = $this->assignAdministratorsToFolders(
                $assignedAdministrators,
                $foldersResult[FolderEnum::FOLDERS]
            );
        }

        return [$folders, $foldersResult[FolderEnum::META][FolderEnum::TOTAL]];
    }

    private function setExtraFilters(array $data): array
    {
        if (isset($data[InternalApiFolderEnum::TEXT_SEARCH_FIELDS])) {
            $textSearchFields = explode(',', $data[InternalApiFolderEnum::TEXT_SEARCH_FIELDS]);
            if (in_array(InternalApiFolderEnum::PERSON_DATE_OF_BIRTH, $textSearchFields)) {
                if (isset($data[InternalApiFolderEnum::FILTERS]) && !empty($data[InternalApiFolderEnum::FILTERS])) {
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

    private function getUserId(array $data, ?int $viewCriteria = null): ?int
    {
        $loggedUserId = $this->authenticator->getLoggedUserData()[UserEnum::USER_ID];
        $userIdFromFilters = isset($data[FolderEnum::FILTERS])
            ? $this->getUserIdFromFilters(explode(",", $data[FolderEnum::FILTERS]))
            : null;

        if (is_null($viewCriteria) || $viewCriteria === FolderEnum::VIEW_CRITERIA_MY_FOLDERS) {
            return $loggedUserId;
        }

        return $userIdFromFilters;
    }

    private function getUserIdFromFilters(array $filters): ?int
    {
        foreach ($filters as $filter) {
            $filterArray = explode(':', $filter);

            if ($filterArray[0] === FolderEnum::USER_ID) {
                return (int) $filterArray[1];
            }
        }

        return null;
    }

    private function handleQueryParameters(array $queryParameters, ?int $userId = null): array
    {
        $view = isset($queryParameters[FolderEnum::VIEW]) ? (int) $queryParameters[FolderEnum::VIEW] : null;

        foreach ($queryParameters as $parameterKey => $parameterValue) {
            if (in_array($parameterKey, [FolderEnum::VIEW, FolderEnum::VIEW_CRITERIA])) {
                unset($queryParameters[$parameterKey]);
            }
            if ($parameterKey === FolderEnum::FILTERS) {
                $queryParameters[FolderEnum::FILTERS] = implode(
                    ",",
                    $this->updateFilters(explode(",", $parameterValue), $view)
                );
            }
        }

        if (!isset($queryParameters[FolderEnum::FILTERS])) {
            $queryParameters[FolderEnum::FILTERS] = implode(",", $this->updateFilters([], $view));
        }

        if (!is_null($userId)) {
            $queryParameters[AdministratorEnum::ADMINISTRATOR_ID_CAMEL_CASE] = $userId;
        }

        return $queryParameters;
    }

    private function updateFilters(?array $filters, ?int $view): array
    {
        foreach ($filters as $filterKey => $filterValue) {
            $filterArray = explode(':', $filterValue);

            if ($filterArray[0] === FolderEnum::USER_ID) {
                unset($filters[$filterKey]);
            }
        }

        return is_null($view) ? $filters : $this->addWorkflowStatusToFilters($filters, $view);
    }

    private function addWorkflowStatusToFilters(array $filters, int $view): array
    {
        switch ($view) {
            case FolderEnum::VIEW_TO_BE_TREATED:
                return array_merge($filters, FolderEnum::VIEW_TO_BE_TREATED_TAB);
            case FolderEnum::VIEW_IN_TREATMENT:
                return array_merge($filters, FolderEnum::VIEW_IN_TREATMENT_TAB);
            case FolderEnum::VIEW_TO_BE_TREATED_SUPERVISOR:
                return array_merge($filters, FolderEnum::VIEW_TO_BE_TREATED_SUPERVISOR_TAB);
            default:
                return $filters;
        }
    }

    private function getReturnedViewCriteria(array $folders, ?int $viewCriteria): int
    {
        return !is_null($viewCriteria)
            ? $viewCriteria
            : (empty($folders)
                ? FolderEnum::VIEW_CRITERIA_ALL_FOLDERS
                : FolderEnum::VIEW_CRITERIA_MY_FOLDERS
            );
    }
}
