<?php

declare(strict_types=1);

namespace App\Fetcher;

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
        return $this->documentFacade->getDocumentsByFolderId($folderId);
    }
}
