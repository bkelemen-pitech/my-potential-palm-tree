<?php

declare(strict_types=1);

namespace App\Client\InternalApi;

use App\Model\InternalApi\Folder\GetFolderByIdResponse;
use App\Model\InternalApi\Person\PersonsByFolderIdResponse;

class FoldersClient extends InternalApiClient
{
    public const PATH_GET_FOLDER_BY_ID = '/folders/getfolder/folder-id/';
    public const PATH_GET_PERSONS_BY_FOLDER = '/folders/persons/folder-id/';

    public function getFolderById(int $folderId)
    {
        $response = $this->get(
            $this->getFullUrl(self::PATH_GET_FOLDER_BY_ID) . $folderId
        );

        return $this->serializer->deserialize($response, GetFolderByIdResponse::class, 'json');
    }

    public function getPersonsByFolderId(int $folderId, array $queryParams = []): PersonsByFolderIdResponse
    {
        $response = $this->get(
            $this->getFullUrl(self::PATH_GET_PERSONS_BY_FOLDER) . $folderId,
            $queryParams
        );

        return $this->serializer->deserialize($response, PersonsByFolderIdResponse::class, 'json');
    }
}
