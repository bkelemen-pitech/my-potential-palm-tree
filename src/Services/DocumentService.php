<?php

declare(strict_types=1);

namespace App\Services;

use App\Exception\InvalidDataException;
use App\Facade\InternalApi\DocumentFacade;
use App\Fetcher\DocumentFetcher;
use Kyc\InternalApiBundle\Model\Request\Document\TreatDocumentModel;
use Kyc\InternalApiBundle\Model\Response\Document\DocumentModelResponse;
use Kyc\InternalApiBundle\Service\DocumentService as InternalApiDocumentService;
use Symfony\Component\Serializer\SerializerInterface;

class DocumentService
{
    protected DocumentFetcher $documentFetcher;
    protected DocumentFacade $documentFacade;
    protected SerializerInterface $serializer;
    protected ValidationService $validationService;
    protected InternalApiDocumentService $internalApiDocumentService;

    public function __construct(
        DocumentFetcher $documentFetcher,
        DocumentFacade $documentFacade,
        SerializerInterface $serializer,
        ValidationService $validationService,
        InternalApiDocumentService $internalApiDocumentService
    ) {
        $this->documentFetcher = $documentFetcher;
        $this->documentFacade = $documentFacade;
        $this->serializer = $serializer;
        $this->validationService = $validationService;
        $this->internalApiDocumentService = $internalApiDocumentService;
    }

    public function getDocumentByUid(string $documentUid, bool $includeFiles): DocumentModelResponse
    {
        return $this->internalApiDocumentService->getDocumentByUid($documentUid, $includeFiles);
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
}
