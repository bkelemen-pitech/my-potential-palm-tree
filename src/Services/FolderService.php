<?php

namespace App\Services;

use App\DTO\Document\DocumentByFolderDTO;
use App\Enum\DocumentEnum;
use App\Enum\FolderEnum;
use App\Exception\ResourceNotFoundException;
use App\Fetcher\DocumentFetcher;
use App\Fetcher\FolderFetcher;
use App\Model\Request\BaseFolderFiltersModel;
use App\Model\Response\BaseResponseFolderModel;
use Symfony\Component\Serializer\SerializerInterface;

class FolderService
{
    protected SerializerInterface $serializer;
    protected ValidationService $validationService;
    protected FolderFetcher $folderFetcher;
    protected DocumentFetcher $documentFetcher;
    protected DocumentService $documentService;

    public function __construct(
        FolderFetcher $folderFetcher,
        SerializerInterface $serializer,
        ValidationService $validationService,
        DocumentFetcher $documentFetcher,
        DocumentService $documentService
    ) {
        $this->folderFetcher = $folderFetcher;
        $this->serializer = $serializer;
        $this->validationService = $validationService;
        $this->documentFetcher = $documentFetcher;
        $this->documentService = $documentService;
    }

    public function getFolders(array $data): array
    {
        $folderFiltersModel = $this->serializer->deserialize(
            json_encode($data),
            BaseFolderFiltersModel::class,
            'json'
        );
        $this->validationService->validate($folderFiltersModel);

        $data = $this->folderFetcher->getFoldersWithFilters($folderFiltersModel);
        $restructuredFolderArray = [];

        foreach ($data[FolderEnum::FOLDERS] as $folder) {
            $restructuredFolderArray[] = $this->serializer->deserialize(
                json_encode($folder),
                BaseResponseFolderModel::class,
                'json'
            )->toArray();
        }

        return [
            FolderEnum::FOLDERS => $restructuredFolderArray,
            FolderEnum::META => $data[FolderEnum::META],
        ];
    }

    public function getDocuments(int $folderId): array
    {
        $internalApiDocuments = $this->documentFetcher->getDocumentsByFolder($folderId);
        $documents = $this->serializer->deserialize(
            $this->serializer->serialize($internalApiDocuments, 'json'),
            DocumentByFolderDTO::class . '[]',
            'json'
        );
        $notDeletedDocuments = array_filter(
            $documents,
            function ($document) {
                return $document->getStatus() !== DocumentEnum::DELETED;
            }
        );
        if (empty($notDeletedDocuments)) {
            throw new ResourceNotFoundException(
                sprintf('No documents found for folder id %s.', $folderId)
            );
        }

        $documentSetList = $this->documentService->extractDocumentList($notDeletedDocuments);

        return $this->documentService->getInfoForDocuments($documentSetList);
    }
}