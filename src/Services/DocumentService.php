<?php

declare(strict_types=1);

namespace App\Services;

use App\Exception\InvalidDataException;
use Kyc\InternalApiBundle\Model\Request\Document\TreatDocumentModel;
use Kyc\InternalApiBundle\Model\Response\Document\DocumentModelResponse;
use Kyc\InternalApiBundle\Service\DocumentService as InternalApiDocumentService;
use Symfony\Component\Serializer\SerializerInterface;

class DocumentService
{
    protected SerializerInterface $serializer;
    protected ValidationService $validationService;
    protected InternalApiDocumentService $internalApiDocumentService;

    public function __construct(
        SerializerInterface $serializer,
        ValidationService $validationService,
        InternalApiDocumentService $internalApiDocumentService
    ) {
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
            $this->internalApiDocumentService->treatDocument($treatDocumentData);
        } catch (\Exception $exception) {
            throw new InvalidDataException($exception->getMessage());
        }
    }
}
