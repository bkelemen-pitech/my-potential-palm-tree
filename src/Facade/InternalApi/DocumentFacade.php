<?php

declare(strict_types=1);

namespace App\Facade\InternalApi;

use App\Client\InternalApi\DocumentClient;
use App\Exception\InvalidDataException;
use App\Exception\NoDataException;
use App\Exception\ResourceNotFoundException;
use App\Model\InternalApi\Document\DocumentByFolder;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

class DocumentFacade
{
    protected DocumentClient $client;

    public function __construct(DocumentClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return DocumentByFolder[]
     */
    public function getDocumentsByFolderId(int $folderId): array
    {
        try {
            $documentsResponse = $this->client->getDocumentsByFolderId($folderId);
            
            return $documentsResponse->getResource();
        } catch (HttpExceptionInterface $exception) {
            throw new InvalidDataException($exception->getMessage());
        }
    }

    public function getDocuments(string $documentUid, bool $includeFiles): array
    {
        try {
            $documents = $this->client->getDocumentsByUid($documentUid, $includeFiles);
            $resource = $documents->getResource();

            if (0 === count($resource)) {
                throw new ResourceNotFoundException(sprintf('No document found for documentUid %s.', $documentUid));
            }

            return $resource;
        } catch (HttpExceptionInterface $exception) {
            throw new InvalidDataException($exception->getMessage());
        }
    }
}
