<?php

declare(strict_types=1);

namespace App\Client\InternalApi;

use App\Enum\DocumentEnum;
use App\Model\InternalApi\Document\DocumentsByFolderResponse;
use App\Model\Request\Document\TreatDocumentModel;

class DocumentClient extends InternalApiClient
{
    public const PATH_GET_DOCUMENTS_BY_FOLDER_ID = '/documents/getdocuments/folder-id/';
    public const PATH_TREAT_DOCUMENTS = '/documents/treat';

    public function getDocumentsByFolderId(int $folderId): DocumentsByFolderResponse
    {
        $serviceResults = $this->get(
            $this->getFullUrl(self::PATH_GET_DOCUMENTS_BY_FOLDER_ID . $folderId),
        );
        
        return $this->serializer->deserialize($serviceResults, DocumentsByFolderResponse::class, 'json');
    }

    public function treatDocument(TreatDocumentModel $treatDocumentParams): void
    {
        $this->post(
            $this->getFullUrl(self::PATH_TREAT_DOCUMENTS),
            [],
            [
                DocumentEnum::BEPREMS_DOCUMENT_UID => $treatDocumentParams->getDocumentUid(),
                DocumentEnum::BEPREMS_DOCUMENT_STATUS => DocumentEnum::TREATED,
                DocumentEnum::BEPREMS_DOCUMENT_VERIFICATION_STATUS2 => $treatDocumentParams->getStatusVerification2(),
            ]
        );
    }
}
