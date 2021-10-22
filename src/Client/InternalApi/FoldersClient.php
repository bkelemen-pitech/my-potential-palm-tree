<?php

declare(strict_types=1);

namespace App\Client\InternalApi;

use App\Model\InternalApi\Folder\GetFoldersResponse;

class FoldersClient extends InternalApiClient
{
    public const PATH_GET_FOLDERS = '/folders/get';
    
    public function getFolders(array $queryParams = []): GetFoldersResponse
    {
        $response = $this->get(
            $this->getFullUrl(self::PATH_GET_FOLDERS),
            $queryParams
        );
        
        return $this->serializer->deserialize($response, GetFoldersResponse::class, 'json');
    }
}
