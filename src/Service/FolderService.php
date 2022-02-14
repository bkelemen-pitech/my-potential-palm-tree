<?php

namespace App\Service;

use App\Enum\AdministratorEnum;
use App\Enum\FolderEnum;
use App\Enum\PersonEnum;
use App\Enum\UserEnum;
use App\Exception\InvalidDataException;
use App\Exception\ResourceNotFoundException;
use App\Model\Request\BaseFolderFiltersModel;
use App\Security\JWTTokenAuthenticator;
use Kyc\InternalApiBundle\Enum\FolderEnum as InternalApiFolderEnum;
use Kyc\InternalApiBundle\Enum\WorkflowStatusEnum;
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

    public function getFoldersByView(array $data): array
    {
        $view = isset($data[FolderEnum::VIEW]) ? (int) $data[FolderEnum::VIEW] : null;

        switch ($view) {
            case FolderEnum::VIEW_TO_BE_TREATED:
            case FolderEnum::VIEW_TO_BE_TREATED_SUPERVISOR:
                return $this->getToBeTreatedFolders($data);
            case FolderEnum::VIEW_IN_TREATMENT:
                return $this->getInTreatmentFolders($data);
            default:
                return [
                    FolderEnum::FOLDERS => [],
                    FolderEnum::META => [
                        FolderEnum::TOTAL => 0,
                        FolderEnum::VIEW_CRITERIA => 1,
                    ],
                ];
        }
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

            if ($folderById->getWorkflowStatus() == WorkflowStatusEnum::STATUT_WORKFLOW_TRAITER_PAR_WEBHELP) {
                try {
                    $administratorId = $this->authenticator->getLoggedUserData()[UserEnum::USER_ID];

                    $this->internalApiFolderService->assignAdministratorToFolder($administratorId, $folderId);

                    $this->updateWorkflowStatus(
                        $folderById->getId(),
                        [InternalApiFolderEnum::WORKFLOW_STATUS_CAMEL_CASE => WorkflowStatusEnum::STATUT_WORKFLOW_PRISE_EN_CHARGE_PAR_WEBHELP],
                        $administratorId
                    );
                    $folderById->setWorkflowStatus(WorkflowStatusEnum::STATUT_WORKFLOW_PRISE_EN_CHARGE_PAR_WEBHELP);
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
            ->setStatusWorkflow($data[InternalApiFolderEnum::WORKFLOW_STATUS_CAMEL_CASE] ?? null)
            ->setAllowBack($data[InternalApiFolderEnum::ALLOW_BACK_DB_CAMEL_CASE] ?? null);

        $this->internalApiFolderService->updateStatusWorkflow($updateStatusWorkflowModel);
    }

    public function dissociateFolder(int $folderId)
    {
        $folder = $this->internalApiFolderService->getFolderById($folderId);
        if ($folder->getWorkflowStatus() <= WorkflowStatusEnum::STATUT_WORKFLOW_TRAITER_PAR_WEBHELP) {
            throw new InvalidDataException('The folder cannot be dissociated.');
        }

        try {
            $this->updateWorkflowStatus(
                $folderId,
                [
                    InternalApiFolderEnum::WORKFLOW_STATUS_CAMEL_CASE => WorkflowStatusEnum::STATUT_WORKFLOW_TRAITER_PAR_WEBHELP,
                    InternalApiFolderEnum::ALLOW_BACK_DB_CAMEL_CASE => true,
                ]
            );
        } catch (\Exception $exception) {
            throw new InvalidDataException('The folder cannot be dissociated because of its workflow status.');
        }

        $dissociateFolderModel = new DissociateFolderModel();
        $dissociateFolderModel->setFolderId($folderId);
        $this->internalApiFolderService->dissociateFolder($dissociateFolderModel);
    }

    public function getFoldersCount(): array
    {
        $filters = $this->getFoldersCountFilters();
        $foldersCount = $this->internalApiFolderService->getFoldersCount([FolderEnum::FILTERS => $filters]);

        return $this->remapFoldersCountView($foldersCount);
    }

    private function getToBeTreatedFolders(array $data): array
    {
        $data = $this->handleQueryParameters($data);
        $data = $this->setExtraFilters($data);
        $folderFiltersModel = $this->serializer->deserialize(
            \json_encode($data),
            BaseFolderFiltersModel::class,
            'json'
        );

        $foldersResponse = $this->internalApiFolderService->getFolders($folderFiltersModel);
        $folders = $this->addAdministratorNamesToFolders($foldersResponse);

        return [
            FolderEnum::FOLDERS => $folders,
            FolderEnum::META => [
                FolderEnum::TOTAL => $foldersResponse[FolderEnum::META][FolderEnum::TOTAL],
                FolderEnum::VIEW_CRITERIA => FolderEnum::VIEW_CRITERIA_ALL_FOLDERS,
            ],
        ];
    }

    private function getInTreatmentFolders(array $data): array
    {
        $viewCriteria = isset($data[FolderEnum::VIEW_CRITERIA]) ? (int) $data[FolderEnum::VIEW_CRITERIA] : null;
        $userId = $this->getUserId($data, $viewCriteria);

        $data = $this->handleQueryParameters($data, $userId);
        $data = $this->setExtraFilters($data);
        $folderFiltersModel = $this->serializer->deserialize(
            \json_encode($data),
            BaseFolderFiltersModel::class,
            'json'
        );

        $foldersResponse = $this->internalApiFolderService->getFolders($folderFiltersModel);
        $folders = $foldersResponse[FolderEnum::FOLDERS];

        if (is_null($userId) || (is_null($viewCriteria) && empty($folders))) {
            if (is_null($viewCriteria) && empty($folders)) {
                unset($data[AdministratorEnum::ADMINISTRATOR_ID_CAMEL_CASE]);
                $folderFiltersModelWithoutAdministratorId = $this->serializer->deserialize(
                    \json_encode($data),
                    BaseFolderFiltersModel::class,
                    'json'
                );
                $foldersResponse = $this->internalApiFolderService->getFolders(
                    $folderFiltersModelWithoutAdministratorId
                );
            }
            $folders = $this->addAdministratorNamesToFolders($foldersResponse);
        }

        return [
            FolderEnum::FOLDERS => $folders,
            FolderEnum::META => [
                FolderEnum::TOTAL => $foldersResponse[FolderEnum::META][FolderEnum::TOTAL],
                FolderEnum::VIEW_CRITERIA => $this->getReturnedViewCriteria($folders, $viewCriteria),
            ],
        ];
    }

    private function handleQueryParameters(array $queryParameters, ?int $userId = null): array
    {
        $view = isset($queryParameters[FolderEnum::VIEW]) ? (int) $queryParameters[FolderEnum::VIEW] : null;

        foreach ($queryParameters as $parameterKey => $parameterValue) {
            if (in_array($parameterKey, FolderEnum::GET_FOLDERS_PARAMETERS_TO_UNSET)) {
                unset($queryParameters[$parameterKey]);
            }
        }

        $queryParameters[FolderEnum::FILTERS] = $this->updateFilters(
            $queryParameters[FolderEnum::FILTERS] ?? [],
            $view
        );

        if (!is_null($userId)) {
            $queryParameters[AdministratorEnum::ADMINISTRATOR_ID_CAMEL_CASE] = $userId;
        }

        return $queryParameters;
    }

    private function setExtraFilters(array $data): array
    {
        if (isset($data[InternalApiFolderEnum::TEXT_SEARCH_FIELDS])) {
            $textSearchFields = explode(',', $data[InternalApiFolderEnum::TEXT_SEARCH_FIELDS]);
            if (in_array(InternalApiFolderEnum::PERSON_DATE_OF_BIRTH, $textSearchFields)) {
                $data[InternalApiFolderEnum::FILTERS][InternalApiFolderEnum::PERSON_TYPE_ID][] = PersonEnum::MAIN_PHYSICAL_PERSON_TYPE_ID;
            }
        }

        return $data;
    }

    private function addAdministratorNamesToFolders(array $foldersResponse): array
    {
        $folderIds = [];

        foreach ($foldersResponse[FolderEnum::FOLDERS] as $folder) {
            $folderIds[] = $folder->getFolderId();
        }

        $assignedAdministrators = $this->getAssignedAdministratorsByFolderIds($folderIds);

        return $this->assignAdministratorsToFolders(
            $assignedAdministrators,
            $foldersResponse[FolderEnum::FOLDERS]
        );
    }

    private function updateFilters(?array $filters, ?int $view): array
    {
        foreach ($filters as $filterKey => $filterValue) {
            if (in_array($filterKey, FolderEnum::GET_FOLDERS_FILTER_PARAMETERS_TO_UNSET)) {
                unset($filters[$filterKey]);
            }
        }

        return is_null($view) ? $filters : $this->addWorkflowStatusToFilters($filters, $view);
    }

    private function addWorkflowStatusToFilters(array $filters, int $view): array
    {
        return array_merge($filters, FolderEnum::WORKFLOW_STATUS_BY_VIEW[$view]);
    }

    private function getReturnedViewCriteria(array $folders, ?int $viewCriteria): int
    {
        if (!is_null($viewCriteria)) {
            return $viewCriteria;
        }

        return empty($folders) ? FolderEnum::VIEW_CRITERIA_ALL_FOLDERS : FolderEnum::VIEW_CRITERIA_MY_FOLDERS;
    }

    private function getUserId(array $data, ?int $viewCriteria = null): ?int
    {
        if (is_null($viewCriteria) || $viewCriteria === FolderEnum::VIEW_CRITERIA_MY_FOLDERS) {
            return (int) $this->authenticator->getLoggedUserData()[UserEnum::USER_ID];
        }

        return $data[FolderEnum::FILTERS][FolderEnum::USER_ID][0] ?? null;
    }

    private function getFoldersCountFilters(): array
    {
        $filters = [];

        foreach (FolderEnum::FOLDER_VIEWS as $view) {
            $filters[FolderEnum::VIEW . '_' . $view] = FolderEnum::WORKFLOW_STATUS_BY_VIEW[$view];
        }

        return $filters;
    }

    private function remapFoldersCountView($foldersCount): array
    {
        $response = [];
        foreach ($foldersCount[FolderEnum::FOLDERS] as $elements) {
            $response[FolderEnum::FOLDERS][] = self::getNumericalValuesForView($elements);
        }

        return $response;
    }

    private function getNumericalValuesForView(array $folderCountProperties): array
    {
        $response = [];
        foreach ($folderCountProperties as $key => $value) {
            if ($key === FolderEnum::VIEW) {
                $response[$key] = (int) explode('_', $value)[1];
                continue;
            }
            $response[$key] = $value;
        }

        return $response;
    }
}
