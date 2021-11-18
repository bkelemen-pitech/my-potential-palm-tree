<?php

declare(strict_types=1);

namespace App\Model\Person;

use Symfony\Component\Validator\Constraints as Assert;

class AddPersonModel
{
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    protected $agencyId = null;

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    protected $userFolderId = null;

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    protected $personTypeId = null;

    /**
     * @Assert\Type(type="string")
     * @Assert\Length(max = 60)
     */
    protected $firstName = null;

    /**
     * @Assert\Type(type="string")
     * @Assert\Length(max = 60)
     */
    protected $lastName = null;

    public function getUserFolderId()
    {
        return $this->userFolderId;
    }

    public function setUserFolderId($userFolderId): AddPersonModel
    {
        $this->userFolderId = $userFolderId;

        return $this;
    }

    public function getAgencyId()
    {
        return $this->agencyId;
    }

    public function setAgencyId($agencyId): AddPersonModel
    {
        $this->agencyId = $agencyId;

        return $this;
    }

    public function getPersonTypeId()
    {
        return $this->personTypeId;
    }

    public function setPersonTypeId($personTypeId): AddPersonModel
    {
        $this->personTypeId = $personTypeId;

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName): AddPersonModel
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName): AddPersonModel
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function toArray(): array
    {
        $data = [
            'agence_id' => $this->agencyId,
            'personne_type_id' => $this->personTypeId,
            'nom' => $this->lastName,
            'prenom' => $this->firstName,
            'user_dossier_id' => $this->userFolderId,
        ];

        return array_filter($data, function ($field) { return !is_null($field);});
    }
}
