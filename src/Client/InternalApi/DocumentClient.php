<?php

declare(strict_types=1);

namespace App\Client\InternalApi;

use App\Enum\BepremsEnum;
use App\Model\InternalApi\Document\DocumentResponse;
use App\Model\InternalApi\Document\DocumentsByFolderResponse;

class DocumentClient extends InternalApiClient
{
    public const PATH_DOCUMENTS = '/documents';
    public const PATH_GET_DOCUMENTS_BY_FOLDER_ID = '/documents/getdocuments/folder-id/';

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
                BepremsEnum::DOCUMENT_UID_PARAM => $documentUid,
                BepremsEnum::DOCUMENT_INCLUDE_FILES_PARAM => $includeFiles,
            ]
        );

        return $this->serializer->deserialize($serviceResults, DocumentResponse::class, 'json');
    }
}
