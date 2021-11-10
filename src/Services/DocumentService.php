<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\document\DocumentByFolderDTO;
use App\Enum\DocumentEnum;
use App\Fetcher\DocumentFetcher;
use App\Helper\DocumentHelper;

class DocumentService
{
    protected DocumentFetcher $documentFetcher;

    public function __construct(DocumentFetcher $documentFetcher)
    {
        $this->documentFetcher = $documentFetcher;
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

    private function extractLatestDocumentWithHighestStatusSameType(array $documents): array
    {
        $newestDocument = [];
        /** @var DocumentByFolderDTO $document */
        foreach ($documents as $document) {
            $type = $document->getDocumentTypeId();
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
        }
        if (DocumentHelper::isValid($document)) {
            $result[DocumentEnum::DOCUMENT_STATUS] = DocumentEnum::VALID;
        }
        if (DocumentHelper::isInvalid($document, $personStatusVerification)) {
            $result[DocumentEnum::DOCUMENT_STATUS] = DocumentEnum::INVALID;
        }

        return $result;
    }
}
