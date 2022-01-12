<?php

declare(strict_types=1);

namespace App\Services;

use App\Exception\InvalidDataException;
use Kyc\InternalApiBundle\Model\Request\Document\MergeDocumentModel;
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
        InternalApiDocumentService $internalApiDocumentService
    ) {
        $this->serializer = $serializer;
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

    public function mergeDocuments($data): void
    {
        try {
            $mergeDocumentModelData = $this->serializer->deserialize(json_encode($data), MergeDocumentModel::class, 'json');
            $this->internalApiDocumentService->mergeDocuments($mergeDocumentModelData);
        } catch (\Exception $exception) {
            throw new InvalidDataException($exception->getMessage());
        }
    }
}
