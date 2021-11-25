<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\document\DocumentByFolderDTO;
use App\DTO\Document\DocumentDTO;
use App\Enum\DocumentEnum;
use App\Exception\InvalidDataException;
use App\Facade\InternalApi\DocumentFacade;
use App\Fetcher\DocumentFetcher;
use App\Helper\DocumentHelper;
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

    /**
     * @param DocumentByFolderDTO[] $documents
     */
    public function extractDocumentList(array $documents): array
    {
        $documentSetList = [];
        // Group documents based on master document id but only if they are of the same type.
        // Otherwise they need to be separated
        foreach ($documents as $document) {
            if ($document->getMasterDocumentId() !== 0) {
                $master = $documentSetList[$document->getMasterDocumentId()][0] ?? null;
                if ($master && $master->getDocumentTypeId() === $document->getDocumentTypeId()) {
                    $documentSetList[$document->getMasterDocumentId()][] = $document;
                } else {
                    $documentSetList[$document->getDocumentId()][] = $document;
                }
            } else {
                $documentSetList[$document->getDocumentId()][] = $document;
            }
        }

        return $documentSetList;
    }

    public function getInfoForDocuments(array $documentSetList): array
    {
        $result = [];
        $documentToFilter = $this->filterDocuments($documentSetList);
        $newestDocuments = $this->extractLatestDocumentWithHighestStatusSameType($documentToFilter);

        foreach ($newestDocuments as $document) {
            $result[] = $this->prepareDocumentsInfos($document);
            if (!empty($document->getSlaves())) {
                foreach ($document->getSlaves() as $slaveDocument) {
                    $result[] = $this->prepareDocumentsInfos($slaveDocument);
                }
            }
        }

        return $result;
    }

    public function prepareDocumentsInfos(DocumentByFolderDTO $document): array
    {
        $documentStatus = DocumentHelper::getStatutVerification(
            $document->getStatusVerification2(),
            $document->getDocumentTypeId()
        );

        $result = [
            DocumentEnum::DOCUMENT_NAME => $document->getName(),
            DocumentEnum::DOCUMENT_TYPE => $document->getDocumentTypeId(),
            DocumentEnum::DOCUMENT_UID => $document->getDocumentUid(),
            DocumentEnum::DOCUMENT_ID => $document->getDocumentId(),
            DocumentEnum::DOCUMENT_VERIFICATION_STATUS => $documentStatus,
            DocumentEnum::DOCUMENT_PERSON_ID => $document->getPersonId(),
        ];

        return $this->setDocumentStatus($result, $document);
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

    private function extractLatestDocumentWithHighestStatusSameType(array $documents): array
    {
        $newestDocument = [];
        /** @var DocumentByFolderDTO $document */
        foreach ($documents as $document) {
            // As multiple types of persons can have the same type of ID the uniqueness has to be tied to the person's ID
            $type = $document->getPersonId() . '-' . $document->getDocumentTypeId();

            if (
                !isset($newestDocument[$type])
                || $document->getStatusVerification2() > $newestDocument[$type]->getStatusVerification2()
                || ($document->getStatusVerification2() === $newestDocument[$type]->getStatusVerification2()
                    && $document->getCreation()->getTimestamp() > $newestDocument[$type]->getCreation()->getTimestamp()
                )
            ) {
                $newestDocument[$type] = $document;
            }
        }

        return $newestDocument;
    }

    private function filterDocuments(array $documentSetList): array
    {
        return array_map(
            function ($documentSet) {
                // if there is a master document, return it and save the slave documents to have access to them if needed
                $masterDocument = $documentSet[0];
                if (count($documentSet) > 1) {
                    array_shift($documentSet);
                    $masterDocument->setSlaves($documentSet);
                }

                return $masterDocument;
            },
            $documentSetList
        );
    }

    private function setDocumentStatus(array $result, DocumentByFolderDTO $document): array
    {
        $personStatusVerification = DocumentHelper::getStatutVerification(
            $document->getPersonVerification(),
            $document->getDocumentTypeId()
        );

        if (DocumentHelper::isPending($document)) {
            $result[DocumentEnum::DOCUMENT_STATUS] = DocumentEnum::PENDING;

            return $result;
        }
        if (DocumentHelper::isValid($document)) {
            $result[DocumentEnum::DOCUMENT_STATUS] = DocumentEnum::VALID;

            return $result;
        }
        if (DocumentHelper::isInvalid($document, $personStatusVerification)) {
            $result[DocumentEnum::DOCUMENT_STATUS] = DocumentEnum::INVALID;

            return $result;
        }

        return $result;
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
