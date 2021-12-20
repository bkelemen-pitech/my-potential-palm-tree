<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Document\DocumentDTO;
use App\Exception\InvalidDataException;
use App\Facade\InternalApi\DocumentFacade;
use App\Fetcher\DocumentFetcher;
use App\Model\InternalApi\Document\Document;
use App\Model\Request\Document\TreatDocumentModel;
use Symfony\Component\Serializer\SerializerInterface;

class DocumentService
{
    protected DocumentFetcher $documentFetcher;
    protected DocumentFacade $documentFacade;
    protected SerializerInterface $serializer;
    protected ValidationService $validationService;

    public function __construct(
        DocumentFetcher $documentFetcher,
        DocumentFacade $documentFacade,
        SerializerInterface $serializer,
        ValidationService $validationService

    ) {
        $this->documentFetcher = $documentFetcher;
        $this->documentFacade = $documentFacade;
        $this->serializer = $serializer;
        $this->validationService = $validationService;
    }

    public function getDocumentByUid(string $documentUid, bool $includeFiles): DocumentDTO
    {
        $internalApiDocuments = $this->documentFetcher->getDocumentsByUid($documentUid, $includeFiles);
        $documentDTO = $this->serializer->deserialize(
            $this->serializer->serialize($internalApiDocuments[0], 'json'),
            DocumentDTO::class,
            'json'
        );

        if (count($internalApiDocuments) > 1) {
            $this->addVersoData($internalApiDocuments[1], $documentDTO, $includeFiles);
        }

        return $documentDTO;
    }

    public function treatDocument($data): void
    {
        try {
            $treatDocumentData = $this->serializer->deserialize(json_encode($data), TreatDocumentModel::class, 'json');
            $this->validationService->validate($treatDocumentData);
            $this->documentFacade->treatDocument($treatDocumentData);
        } catch (\Exception $exception) {
            throw new InvalidDataException($exception->getMessage());
        }
    }

    private function addVersoData(Document $versoDocument, DocumentDTO $documentDTO, bool $includeFiles)
    {
        $documentDTO->setNameVerso($versoDocument->getNom());
        if ($includeFiles) {
            $documentDTO->setContentVerso($versoDocument->getDocumentFile());
        }

        return $documentDTO;
    }
}
