<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Person\PersonDTO;
use App\DTO\Person\PersonInfoDTO;
use App\Model\InternalApi\Person\Person;

class PersonService
{
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
