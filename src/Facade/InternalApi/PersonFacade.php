<?php

declare(strict_types=1);

namespace App\Facade\InternalApi;

use App\Client\InternalApi\PersonClient;
use App\Exception\InvalidDataException;
use App\Exception\ResourceNotFoundException;
use App\Model\InternalApi\Person\AssignDocumentToPersonModel;
use Symfony\Component\HttpFoundation\Response;

class PersonFacade
{
    protected PersonClient $personClient;

    public function __construct(PersonClient $personClient)
    {
        $this->personClient = $personClient;
    }

    public function assignDocument(AssignDocumentToPersonModel $assignDocumentToPersonParams): void
    {
        try {
            $this->personClient->assignDocument($assignDocumentToPersonParams->toArray());
        } catch (\Exception $exception) {
            if ($exception->getCode() == Response::HTTP_NOT_FOUND) {
                throw new ResourceNotFoundException($exception->getMessage(), $exception->getCode());
            }
            throw new InvalidDataException($exception->getMessage(), $exception->getCode());
        }
    }
}
