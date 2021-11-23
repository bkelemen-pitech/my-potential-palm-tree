<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Person\PersonDTO;
use App\DTO\Person\PersonInfoDTO;
use App\Enum\PersonEnum;
use App\Exception\InvalidDataException;
use App\Facade\InternalApi\PersonFacade;
use App\Model\InternalApi\Person\Person;
use App\Model\Person\AddPersonModel;
use App\Model\Person\AssignDocumentToPersonModel;
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

    public function transformPersonToDTO(Person $person): PersonDTO
    {
        $personInfos = [];
        foreach ($person->getPersonInfos() as $personInfo) {
            $personInfoDTO = new PersonInfoDTO();
            $personInfoDTO
                ->setNameInfo($personInfo->getNomInfo())
                ->setDataInfo($personInfo->getDataInfo())
                ->setSource($personInfo->getSource());
            $personInfos[] = $personInfoDTO;
        }

        $personDTO = new PersonDTO();
        $personDTO
            ->setPersonId($person->getPersonneId())
            ->setLastName($person->getNom())
            ->setFirstName($person->getPrenom())
            ->setPersonTypeId($person->getPersonneTypeId())
            ->setPersonUid($person->getPersonneUid())
            ->setDateOfBirth($person->getDateNaissance())
            ->setPersonInfo($personInfos)
            ->setFolderId($person->getUserDossierId());

        return $personDTO;
    }
}
