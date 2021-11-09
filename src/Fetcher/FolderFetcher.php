<?php

declare(strict_types=1);

namespace App\Fetcher;

use App\DTO\Folder\FolderByIdDTO;
use App\Exception\ResourceNotFoundException;
use App\Facade\InternalApi\FolderFacade;
use App\Model\Request\BaseFolderFiltersInterface;
use App\Services\PersonService;

class FolderFetcher
{
    private FolderFacade $folderFacade;
    private PersonService $personService;

    public function __construct(
        FolderFacade $folderFacade,
        PersonService $personService)
    {
        $this->folderFacade = $folderFacade;
        $this->personService = $personService;
    }

    public function getFoldersWithFilters(BaseFolderFiltersInterface $folderFiltersModel): array
    {
        return $this->folderFacade->getFolders($folderFiltersModel->toArray());
    }

    public function getFolderData(int $folderId, array $filters): FolderByIdDTO
    {
        $internalApiData = $this->folderFacade->getFolderById($folderId);
        $folderByIdDTO = new FolderByIdDTO();
        $folderByIdDTO
            ->setId($internalApiData->getUserDossierId())
            ->setPartnerFolderId($internalApiData->getPartenaireDossierId())
            ->setStatus($internalApiData->getStatut())
            ->setLabel($internalApiData->getLabel())
            ->setWorkflowStatus($internalApiData->getStatutWorkflow())
            ->setSubscription($internalApiData->getAbonnement());

        try {
            $personsByFolderId = $this->folderFacade->getPersonsByFolderId($folderId, $filters);
        } catch (ResourceNotFoundException $exception) {
            $personsByFolderId = [];
        }
        $persons = [];
        foreach ($personsByFolderId as $person) {
            $persons[] = $this->personService->transformPersonToDTO($person);
        }
        $folderByIdDTO->setPersons($persons);

        return $folderByIdDTO;
    }
}
