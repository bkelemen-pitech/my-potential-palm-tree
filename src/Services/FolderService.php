<?php

namespace App\Services;

use App\DTO\Document\DocumentByFolderDTO;
use App\Enum\DocumentEnum;
use App\Enum\FolderEnum;
use App\Fetcher\DocumentFetcher;
use App\Fetcher\FolderFetcher;
use App\Model\Request\BaseFolderFiltersModel;
use Symfony\Component\Serializer\SerializerInterface;
use Kyc\InternalApiBundle\Services\FolderService as InternalApiFolderService;

class FolderService
{
    protected SerializerInterface $serializer;
    protected ValidationService $validationService;
    protected FolderFetcher $folderFetcher;
    protected DocumentFetcher $documentFetcher;
    protected DocumentService $documentService;
    protected InternalApiFolderService $internalApiFolderService;

    public function __construct(
        FolderFetcher $folderFetcher,
        SerializerInterface $serializer,
        ValidationService $validationService,
        DocumentFetcher $documentFetcher,
        DocumentService $documentService,
        InternalApiFolderService $internalApiFolderService
    ) {
        $this->folderFetcher = $folderFetcher;
        $this->serializer = $serializer;
        $this->validationService = $validationService;
        $this->documentFetcher = $documentFetcher;
        $this->documentService = $documentService;
        $this->internalApiFolderService = $internalApiFolderService;
    }

    public function getFolders(array $data): array
    {
        $folderFiltersModel = $this->serializer->deserialize(
            json_encode($data),
            BaseFolderFiltersModel::class,
            'json'
        );

        $data = $this->internalApiFolderService->getFolders($folderFiltersModel);

        return [
            FolderEnum::FOLDERS => $data[FolderEnum::FOLDERS],
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
            return  [];
        }

        $documentSetList = $this->documentService->extractDocumentList($notDeletedDocuments);

        return $this->documentService->getInfoForDocuments($documentSetList);
    }
}
