<?php

declare(strict_types=1);

namespace App\Facade\InternalApi;

use App\Client\InternalApi\FoldersClient;
use App\Exception\InvalidDataException;

class FolderFacade
{
    protected FoldersClient $client;

    public function __construct(FoldersClient $client)
    {
        $this->client = $client;
    }

    public function getFolders(array $queryParams = []): array
    {
        try {
            $folders = $this->client->getFolders($queryParams);
            die(var_dump($folders));

            return $folders->getResource();
        } catch (\Exception $exception) {
            throw new InvalidDataException($exception->getMessage());
        }
    }
}
