<?php

declare(strict_types=1);

namespace App\Client\InternalApi;

use App\Enum\BepremsEnum;
use App\Enum\DocumentEnum;
use App\Model\InternalApi\Document\DocumentResponse;
use App\Model\InternalApi\Document\DocumentsByFolderResponse;
use App\Model\Request\Document\TreatDocumentModel;

class DocumentClient extends InternalApiClient
{
    public const PATH_DOCUMENTS = '/documents';
    public const PATH_GET_DOCUMENTS_BY_FOLDER_ID = '/documents/getdocuments/folder-id/';
    public const PATH_TREAT_DOCUMENTS = '/documents/treat';

    public function getDocumentsByFolderId(int $folderId): DocumentsByFolderResponse
    {
        $serviceResults = $this->get(
            $this->getFullUrl(self::PATH_GET_DOCUMENTS_BY_FOLDER_ID . $folderId),
        );
        
        return $this->serializer->deserialize($serviceResults, DocumentsByFolderResponse::class, 'json');
    }

    public function getDocumentsByUid(string $documentUid, bool $includeFiles): DocumentResponse
    {
        $serviceResults = $this->get(
            $this->getFullUrl(self::PATH_DOCUMENTS),
            [
                BepremsEnum::DOCUMENT_UID => $documentUid,
                BepremsEnum::DOCUMENT_INCLUDE_FILES => $includeFiles,
            ]
        );

        return $this->serializer->deserialize($serviceResults, DocumentResponse::class, 'json');
    }

    public function treatDocument(TreatDocumentModel $treatDocumentParams): void
    {
        $this->post(
            $this->getFullUrl(self::PATH_TREAT_DOCUMENTS),
            [],
            [
                BepremsEnum::DOCUMENT_UID => $treatDocumentParams->getDocumentUid(),
                BepremsEnum::DOCUMENT_STATUS => DocumentEnum::TREATED,
                BepremsEnum::DOCUMENT_VERIFICATION_STATUS2 => $treatDocumentParams->getStatusVerification2(),
            ]
        );
    }
}
