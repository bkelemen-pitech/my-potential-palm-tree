<?php

declare(strict_types=1);

namespace App\Model\InternalApi\Person;

use App\Model\InternalApi\InternalResponse;

class AddPersonResponse extends InternalResponse
{
    protected array $resource;

    public function getResource(): array
    {
        return $this->resource;
    }

    public function setResource(array $resource): AddPersonResponse
    {
        $this->resource = $resource;

        return $this;
    }
}
