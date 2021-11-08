<?php

declare(strict_types=1);

namespace App\DTO\Person;

use Symfony\Component\Serializer\Annotation\Groups;

class PersonDTO
{
    protected int $personId;

    /**
     * @Groups({"readList"})
     */
    protected ?string $lastName;

    /**
     * @Groups({"readList"})
     */
    protected ?string $firstName;

    /**
     * @Groups({"readList"})
     */
    protected ?string $dateOfBirth;

    /**
     * @Groups({"readList"})
     */
    protected ?int $personTypeId;

    /**
     * @Groups({"readList"})
     */
    protected ?string $personUid;

    /**
     * @var PersonInfoDTO[]
     */
    protected array $personInfo = [];

    protected ?int $folderId;

    public function getPersonId(): int
    {
        return $this->personId;
    }

    public function setPersonId(int $personId): PersonDTO
    {
        $this->personId = $personId;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): PersonDTO
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): PersonDTO
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?string $dateOfBirth): PersonDTO
    {
        $this->dateOfBirth = $dateOfBirth ? (new \DateTime($dateOfBirth))->format('c') : null;

        return $this;
    }

    public function getPersonTypeId(): ?int
    {
        return $this->personTypeId;
    }

    public function setPersonTypeId(?int $personTypeId): PersonDTO
    {
        $this->personTypeId = $personTypeId;

        return $this;
    }

    public function getPersonUid(): ?string
    {
        return $this->personUid;
    }

    public function setPersonUid(?string $personUid): PersonDTO
    {
        $this->personUid = $personUid;

        return $this;
    }

    public function getPersonInfo(): array
    {
        return $this->personInfo;
    }

    public function setPersonInfo(array $personInfo): PersonDTO
    {
        $this->personInfo = $personInfo;

        return $this;
    }

    public function getFolderId(): ?int
    {
        return $this->folderId;
    }

    public function setFolderId(?int $folderId): PersonDTO
    {
        $this->folderId = $folderId;

        return $this;
    }
}
