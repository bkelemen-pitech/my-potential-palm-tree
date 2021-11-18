<?php

declare(strict_types=1);

namespace App\Facade\InternalApi;

use App\Client\InternalApi\PersonClient;
use App\Exception\InvalidDataException;
use App\Model\Person\AddPersonModel;

class PersonFacade
{
    protected PersonClient $personClient;

    public function __construct(PersonClient $personClient)
    {
        $this->personClient = $personClient;
    }

    public function addPerson(AddPersonModel $addPersonParams): array
    {
        try {
            $addPersonResponse = $this->personClient->createPerson($addPersonParams->toArray());

            return $addPersonResponse->getResource();
        } catch (\Exception $exception) {
            throw new InvalidDataException($exception->getMessage());
        }
    }
}
