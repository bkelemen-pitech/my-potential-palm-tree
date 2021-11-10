<?php

declare(strict_types=1);

namespace App\Model\InternalApi\Document;

use App\Model\InternalApi\InternalResponse;

class DocumentsByFolderResponse extends InternalResponse
{
    /**
     * @var DocumentByFolder[]
     */
    protected array $resource;

    /**
     * @return DocumentByFolder[]
     */
    public function getResource(): array
    {
        return $this->resource;
    }

    /**
     * @param DocumentByFolder[] $resource
     */
    public function setResource(array $resource): DocumentsByFolderResponse
    {
        $this->resource = $resource;

        return $this;
    }
}
