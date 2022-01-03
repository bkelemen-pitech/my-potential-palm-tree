<?php

namespace App\Services;

use App\Enum\FolderEnum;
use App\Exception\ResourceNotFoundException;
use App\Fetcher\DocumentFetcher;
use App\Model\Request\BaseFolderFiltersModel;
use Kyc\InternalApiBundle\Exception\ResourceNotFoundException as KycResourceNotFoundException;
use Kyc\InternalApiBundle\Model\Response\Folder\FolderByIdModelResponse;
use Kyc\InternalApiBundle\Services\FolderService as InternalApiFolderService;
use Kyc\InternalApiBundle\Services\DocumentService as InternalApiDocumentService;
use Symfony\Component\Serializer\SerializerInterface;

class FolderService
{
    protected SerializerInterface $serializer;
    protected ValidationService $validationService;
    protected DocumentFetcher $documentFetcher;
    protected DocumentService $documentService;
    protected InternalApiFolderService $internalApiFolderService;
    protected InternalApiDocumentService $internalApiDocumentService;

    public function __construct(
        SerializerInterface $serializer,
        ValidationService $validationService,
        DocumentFetcher $documentFetcher,
        DocumentService $documentService,
        InternalApiFolderService $internalApiFolderService,
        InternalApiDocumentService $internalApiDocumentService
    ) {
        $this->serializer = $serializer;
        $this->validationService = $validationService;
        $this->documentFetcher = $documentFetcher;
        $this->documentService = $documentService;
        $this->internalApiFolderService = $internalApiFolderService;
        $this->internalApiDocumentService = $internalApiDocumentService;
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
        return $this->internalApiDocumentService->getDocumentsByFolderId($folderId);
    }

    public function getFolderData(int $folderId, array $filters): FolderByIdModelResponse
    {
        try {
            $folderById = $this->internalApiFolderService->getFolderById($folderId);
            $persons = $this->internalApiFolderService->getPersonsByFolderId($folderId, $filters);
            $folderById->setPersons($persons);

            return $folderById;
        } catch (KycResourceNotFoundException $exception) {
            throw new ResourceNotFoundException($exception->getMessage());
        }
    }
}
