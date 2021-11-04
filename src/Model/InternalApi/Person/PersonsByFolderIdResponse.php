<?php

declare(strict_types=1);

namespace App\Model\InternalApi\Person;

use App\Model\InternalApi\InternalResponse;

class PersonsByFolderIdResponse extends InternalResponse
{
    /**
     * @var Person[]
     */
    protected array $resource;

    /**
     * @return Person[]
     */
    public function getResource(): array
    {
        return $this->resource;
    }

    /**
     * @param Person[] $resource
     */
    public function setResource(array $resource): PersonsByFolderIdResponse
    {
        $this->resource = $resource;

        return $this;
    }
}
