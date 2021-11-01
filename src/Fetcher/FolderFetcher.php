<?php

declare(strict_types=1);

namespace App\Fetcher;

use App\Facade\InternalApi\FolderFacade;
use App\Model\Request\BaseFolderFiltersInterface;

class FolderFetcher
{
    private FolderFacade $folderFacade;

    public function __construct(FolderFacade $folderFacade)
    {
        $this->folderFacade = $folderFacade;
    }

    public function getFoldersWithFilters(BaseFolderFiltersInterface $folderFiltersModel): array
    {
        return $this->folderFacade->getFolders($folderFiltersModel->toArray());
    }
}
