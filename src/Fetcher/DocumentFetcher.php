<?php

declare(strict_types=1);

namespace App\Fetcher;

use App\Exception\ResourceNotFoundException;
use App\Facade\InternalApi\DocumentFacade;

class DocumentFetcher
{
    private DocumentFacade $documentFacade;

    public function __construct(DocumentFacade $documentFacade)
    {
        $this->documentFacade = $documentFacade;
    }

    public function getDocumentsByFolder(int $folderId): array
    {
        $documents = $this->documentFacade->getDocumentsByFolderId($folderId);
        if (empty($documents)) {
            throw new ResourceNotFoundException(
                sprintf('No documents found for folder id %s.', $folderId)
            );
        }

        return $documents;
    }

    public function getDocumentsByUid(string $uid, bool $includeFiles): array
    {
        return $this->documentFacade->getDocuments($uid, $includeFiles);
    }
}
