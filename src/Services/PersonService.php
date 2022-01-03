<?php

declare(strict_types=1);

namespace App\Services;

use App\Enum\PersonEnum;
use App\Exception\InvalidDataException;
use App\Facade\InternalApi\PersonFacade;
use App\Model\InternalApi\Person\AssignDocumentToPersonModel;
use Kyc\InternalApiBundle\Model\InternalApi\Person\AddPersonModel;
use Kyc\InternalApiBundle\Services\PersonService as InternalApiPersonService;
use Symfony\Component\Serializer\SerializerInterface;

class PersonService
{
    protected PersonFacade $personFacade;
    protected SerializerInterface $serializer;
    protected ValidationService $validationService;
    protected InternalApiPersonService $internalApiPersonService;

    public function __construct(
        PersonFacade $personFacade,
        SerializerInterface $serializer,
        ValidationService $validationService,
        InternalApiPersonService $internalApiPersonService
    )
    {
        $this->personFacade = $personFacade;
        $this->serializer = $serializer;
        $this->validationService = $validationService;
        $this->internalApiPersonService = $internalApiPersonService;
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

            return $this->internalApiPersonService->addPerson($addPersonModelData);
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
