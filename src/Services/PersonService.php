<?php

declare(strict_types=1);

namespace App\Services;

use App\Enum\PersonEnum;
use App\Exception\InvalidDataException;
use App\Facade\InternalApi\PersonFacade;
use App\Model\InternalApi\Person\AddPersonModel;
use App\Model\InternalApi\Person\AssignDocumentToPersonModel;
use Symfony\Component\Serializer\SerializerInterface;

class PersonService
{
    protected PersonFacade $personFacade;
    protected SerializerInterface $serializer;
    protected ValidationService $validationService;

    public function __construct(
        PersonFacade $personFacade,
        SerializerInterface $serializer,
        ValidationService $validationService
    )
    {
        $this->personFacade = $personFacade;
        $this->serializer = $serializer;
        $this->validationService = $validationService;
    }

    /**
     * @throws InvalidDataException
     */
    public function addPerson(int $folderId, array $personData): string
    {
        try {
            $addPersonModelData = $this->serializer->deserialize(
                json_encode(array_merge($personData, ['userFolderId' => $folderId])),
                AddPersonModel::class, 'json'
            );
            $this->validationService->validate($addPersonModelData);
            $addPersonResource = $this->personFacade->addPerson($addPersonModelData);

            return $addPersonResource[PersonEnum::BEPREMS_RESPONSE_PERSON_UID];
        } catch (\Exception $exception) {
            throw new InvalidDataException($exception->getMessage());
        }
    }

    public function assignDocument(array $data): void
    {
        $assignDocumentData = $this->serializer->deserialize(
            json_encode($data),
            AssignDocumentToPersonModel::class, 'json'
        );
        $this->validationService->validate($assignDocumentData);
        $this->personFacade->assignDocument($assignDocumentData);
    }
}
