<?php

namespace App\Services;

use App\DTO\Document\DocumentByFolderDTO;
use App\Enum\DocumentEnum;
use App\Enum\FolderEnum;
use App\Exception\ResourceNotFoundException;
use App\Fetcher\DocumentFetcher;
use App\Model\Request\BaseFolderFiltersModel;
use Kyc\InternalApiBundle\Exception\ResourceNotFoundException as KycResourceNotFoundException;
use Kyc\InternalApiBundle\Model\Response\Folder\FolderByIdModelResponse;
use Kyc\InternalApiBundle\Service\FolderService as InternalApiFolderService;
use Symfony\Component\Serializer\SerializerInterface;

class FolderService
{
    protected SerializerInterface $serializer;
    protected ValidationService $validationService;
    protected DocumentFetcher $documentFetcher;
    protected DocumentService $documentService;
    protected InternalApiFolderService $internalApiFolderService;

    public function __construct(
        SerializerInterface $serializer,
        ValidationService $validationService,
        DocumentFetcher $documentFetcher,
        DocumentService $documentService,
        InternalApiFolderService $internalApiFolderService
    ) {
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
