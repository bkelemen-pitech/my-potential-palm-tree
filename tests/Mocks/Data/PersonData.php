<?php

declare(strict_types=1);

namespace App\Tests\Mocks\Data;

use App\DTO\Person\PersonDTO;
use App\DTO\Person\PersonInfoDTO;
use App\Model\InternalApi\Person\Person;
use App\Model\InternalApi\Person\PersonInfo;
use App\Model\InternalApi\Person\PersonsByFolderIdResponse;

class PersonData
{
    public const DEFAULT_PERSON_INFO_DATA = ['dossier', '39', null];
    public const PERSON1_DATA = ['Smith', 'John', '12-01-2020', 1, '1', 30];
    public const PERSON2_DATA = ['Smithy', 'Johny', '12-03-2020', 1, '1', 31];

    public static function createPersonInfoDTO(array $data = self::DEFAULT_PERSON_INFO_DATA)
    {
        return (new PersonInfoDTO())
            ->setNameInfo($data[0])
            ->setDataInfo($data[1])
            ->setSource($data[2]);
    }

    public static function createPersonDTO(bool $withPersonInfo = false, array $data = self::PERSON1_DATA)
    {
        $personDTO = (new PersonDTO())
            ->setLastName($data[0])
            ->setFirstName($data[1])
            ->setDateOfBirth($data[2])
            ->setPersonTypeId($data[3])
            ->setPersonUid($data[4])
            ->setPersonId($data[5]);

        if ($withPersonInfo) $personDTO->setPersonInfo([self::createPersonInfoDTO()]);

        return $personDTO;
    }

    public static function getFolderPersonsDTOByIdTestData()
    {
        return [
          self::createPersonDTO(true),
          self::createPersonDTO(false, self::PERSON2_DATA)
        ];
    }

    public static function createPersonInfoEntity(array $data = self::DEFAULT_PERSON_INFO_DATA)
    {
        return (new PersonInfo())
            ->setNomInfo($data[0])
            ->setDataInfo($data[1])
            ->setSource($data[2]);
    }

    public static function createPersonEntity(bool $withPersonInfo = false, array $data = self::PERSON1_DATA)
    {
        $personDTO = (new Person())
            ->setPrenom($data[0])
            ->setNom($data[1])
            ->setDateNaissance($data[2])
            ->setPersonneTypeId($data[3])
            ->setPersonneUid($data[4])
            ->setPersonneId($data[5]);

        if ($withPersonInfo) $personDTO->setPersonInfos([self::createPersonInfoEntity()]);

        return $personDTO;
    }

    public static function getFolderPersonsEntityByIdTestData()
    {
        return [
            self::createPersonEntity(true),
            self::createPersonEntity(false, self::PERSON2_DATA)
        ];
    }

    public static function createPersonsByFolderIdResponseData(array $persons)
    {
        return (new PersonsByFolderIdResponse())
            ->setCode('OK')
            ->setMsg('Success')
            ->setResource($persons);
    }
}