<?php

declare(strict_types=1);

namespace App\Facade\InternalApi;

use App\Client\InternalApi\FoldersClient;
use App\Exception\InvalidDataException;
use App\Exception\ResourceNotFoundException;
use App\Model\InternalApi\Folder\FolderById;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

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

            return $folders->getResource();
        } catch (\Exception $exception) {
            throw new InvalidDataException($exception->getMessage());
        }
    }

    public function getFolderById(int $folderId): FolderById
    {
        try {
            $folderByIdResponse = $this->client->getFolderById($folderId);

            return $folderByIdResponse->getResource();
        } catch (HttpExceptionInterface $exception) {
            if ($exception->getCode() == Response::HTTP_NOT_FOUND) {
                throw new ResourceNotFoundException(sprintf('Folder with id %s not found', $folderId));
            }

            throw new InvalidDataException($exception->getMessage());
        }
    }

    public function getPersonsByFolderId(int $folderId, array $queryParams = []): array
    {
        try {
            $personsByIdResponse = $this->client->getPersonsByFolderId($folderId, $queryParams);

            return $personsByIdResponse->getResource();
        } catch (HttpExceptionInterface $exception) {
            if ($exception->getCode() == Response::HTTP_NOT_FOUND) {
                throw new ResourceNotFoundException(sprintf('Folder with id %s not found', $folderId));
            }

            throw new InvalidDataException('Request failed because of third party issues.');
        }
    }
}
