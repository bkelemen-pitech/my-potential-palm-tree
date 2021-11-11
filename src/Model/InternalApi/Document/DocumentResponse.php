<?php

declare(strict_types=1);

namespace App\Model\InternalApi\Document;

use App\Model\InternalApi\InternalResponse;

class DocumentResponse extends InternalResponse
{
    /**
     * @var Document[]
     */
    protected array $resource;

    /**
     * @return Document[]
     */
    public function getResource(): array
    {
        return $this->resource;
    }

    /**
     * @param Document[] $resource
     */
    public function setResource(array $resource): DocumentResponse
    {
        $this->resource = $resource;

        return $this;
    }
}
