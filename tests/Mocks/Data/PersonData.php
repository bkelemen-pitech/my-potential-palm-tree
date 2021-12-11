<?php

declare(strict_types=1);

namespace App\Tests\Mocks\Data;

use App\Model\InternalApi\Person\AddPersonResponse;
use App\Model\InternalApi\Person\AddPersonModel;
use App\Model\InternalApi\Person\AssignDocumentToPersonModel;
use Kyc\InternalApiBundle\Model\InternalApi\Person\Person;
use Kyc\InternalApiBundle\Model\InternalApi\Person\PersonInfo;
use Kyc\InternalApiBundle\Model\InternalApi\Person\PersonsByFolderIdResponse;
use Kyc\InternalApiBundle\Model\Response\Person\PersonInfoModelResponse;
use Kyc\InternalApiBundle\Model\Response\Person\PersonModelResponse;

class PersonData
{
    public const DEFAULT_PERSON_UID_TEST_DATA = '6195f4431446f';
    public const DEFAULT_PERSON_INFO_DATA = ['dossier', '39', null];
    public const PERSON1_DATA = ['Smith', 'John', '12-01-2020', 1, '1', 30];
    public const PERSON2_DATA = ['Smithy', 'Johny', '12-03-2020', 1, '1', 31];
    public const ADD_PERSON_DATA = [709, null, null, 1, 1];
    public const ASSIGN_DOCUMENT_DATA = [1, '6196610f9d67', '6184c9672f420'];

    public static function createPersonInfoModelResponse(array $data = self::DEFAULT_PERSON_INFO_DATA): PersonInfoModelResponse
    {
        return (new PersonInfoModelResponse())
            ->setNameInfo($data[0])
            ->setDataInfo($data[1])
            ->setSource($data[2]);
    }

    public static function createPersonModelResponse(bool $withPersonInfo = false, array $data = self::PERSON1_DATA): PersonModelResponse
    {
        $personModel = (new PersonModelResponse())
            ->setLastName($data[0])
            ->setFirstName($data[1])
            ->setDateOfBirth($data[2])
            ->setPersonTypeId($data[3])
            ->setPersonUid($data[4])
            ->setPersonId($data[5]);

        if ($withPersonInfo) $personModel->setPersonInfo([self::createPersonInfoModelResponse()]);

        return $personModel;
    }

    public static function getFolderPersonsModelResponseByIdTestData(): array
    {
        return [
          self::createPersonModelResponse(true),
          self::createPersonModelResponse(false, self::PERSON2_DATA)
        ];
    }

    public static function createInternalApiPersonInfo(array $data = self::DEFAULT_PERSON_INFO_DATA): PersonInfo
    {
        return (new PersonInfo())
            ->setNomInfo($data[0])
            ->setDataInfo($data[1])
            ->setSource($data[2]);
    }

    public static function createInternalApiPerson(bool $withPersonInfo = false, array $data = self::PERSON1_DATA): Person
    {
        $personModel = (new Person())
            ->setPrenom($data[0])
            ->setNom($data[1])
            ->setDateNaissance($data[2])
            ->setPersonneTypeId($data[3])
            ->setPersonneUid($data[4])
            ->setPersonneId($data[5]);

        if ($withPersonInfo) $personModel->setPersonInfos([self::createInternalApiPersonInfo()]);

        return $personModel;
    }

    public static function getInternalApiFolderPersonsByIdTestData()
    {
        return [
            self::createInternalApiPerson(true),
            self::createInternalApiPerson(false, self::PERSON2_DATA)
        ];
    }

    public static function createInternalApiPersonsByFolderIdResponseData(array $persons): PersonsByFolderIdResponse
    {
        return (new PersonsByFolderIdResponse())
            ->setCode('OK')
            ->setMsg('Success')
            ->setResource($persons);
    }

    public static function createAddPersonModel(array $data = self::ADD_PERSON_DATA): AddPersonModel
    {
        return (new AddPersonModel())
            ->setAgencyId($data[0])
            ->setFirstName($data[1])
            ->setLastName($data[2])
            ->setPersonTypeId($data[3])
            ->setUserFolderId($data[4]);
    }

    public static function createAddPersonResponse(array $resource): AddPersonResponse
    {
        return (new AddPersonResponse())
            ->setResource($resource);
    }

    public static function createAssignDocumentToPersonModel(array $data = self::ASSIGN_DOCUMENT_DATA): AssignDocumentToPersonModel
    {
        return (new AssignDocumentToPersonModel())
            ->setFolderId($data[0])
            ->setDocumentUid($data[1])
            ->setPersonUid($data[2]);
    }
}
