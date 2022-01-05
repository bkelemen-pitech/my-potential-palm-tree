<?php

declare(strict_types=1);

namespace App\Services;

use App\Exception\InvalidDataException;
use Kyc\InternalApiBundle\Model\InternalApi\Person\AddPersonModel;
use Kyc\InternalApiBundle\Model\InternalApi\Person\AssignDocumentToPersonModel;
use Kyc\InternalApiBundle\Service\PersonService as InternalApiPersonService;
use Symfony\Component\Serializer\SerializerInterface;

class PersonService
{
    protected SerializerInterface $serializer;
    protected ValidationService $validationService;
    protected InternalApiPersonService $internalApiPersonService;

    public function __construct(
        SerializerInterface $serializer,
        ValidationService $validationService,
        InternalApiPersonService $internalApiPersonService
    )
    {
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
        $this->internalApiPersonService->assignDocument($assignDocumentData);
    }
}
