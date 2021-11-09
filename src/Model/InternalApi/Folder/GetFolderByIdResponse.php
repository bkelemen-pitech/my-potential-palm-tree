<?php

declare(strict_types=1);

namespace App\Model\InternalApi\Folder;

use App\Model\InternalApi\InternalResponse;

class GetFolderByIdResponse extends InternalResponse
{
    protected FolderById $resource;

    public function getResource(): FolderById
    {
        return $this->resource;
    }

    public function setResource(FolderById $resource): GetFolderByIdResponse
    {
        $this->resource = $resource;

        return $this;
    }
}
