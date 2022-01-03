<?php

declare(strict_types=1);

namespace App\Client\InternalApi;

use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

class PersonClient extends InternalApiClient
{
    public const PATH_ASSIGN_DOCUMENT = '/person/assigndocument';

    /**
     * @throws HttpExceptionInterface
     */
    public function assignDocument(array $data): void
    {
        $this->post(
            $this->getFullUrl(self::PATH_ASSIGN_DOCUMENT),
            [],
            $data,
        );
    }
}
