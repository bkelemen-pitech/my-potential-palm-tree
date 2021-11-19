<?php

declare(strict_types=1);

namespace App\Client\InternalApi;

use App\Model\InternalApi\Person\AddPersonResponse;

class PersonClient extends InternalApiClient
{
    public const PATH_CREATE_PERSON = '/person/create';

    public function createPerson(array $data): AddPersonResponse
    {
        $response = $this->post(
            $this->getFullUrl(self::PATH_CREATE_PERSON),
            [],
            $data,
        );

        return $this->serializer->deserialize($response, AddPersonResponse::class, 'json');
    }
}
