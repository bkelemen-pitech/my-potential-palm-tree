<?php

declare(strict_types=1);

namespace App\Model\Response;

use App\Enum\FolderEnum;
use App\Traits\StringTransformationTrait;
use Symfony\Component\Validator\Constraints as Assert;

class BaseResponseFolderModel implements BaseResponseFolderInterface
{
    use StringTransformationTrait;

    /**
     * @Assert\Type(type="int")
     */
    protected ?int $folderId = null;

    /**
     * @Assert\Type(type="string")
     */
    protected ?string $folderName = '';

    /**
     * @Assert\Type(type="string")
     */
    protected ?string $firstName = '';

    /**
     * @Assert\Type(type="string")
     */
    protected ?string $lastName = '';

    public function getUserDossierId(): ?int
    {
        return $this->folderId;
    }

    public function setUserDossierId(?int $folderId): BaseResponseFolderModel
    {
        $this->folderId = $folderId;

        return $this;
    }

    public function getPartenaireDossierId(): ?string
    {
        return $this->folderName;
    }

    public function setPartenaireDossierId(?string $folderName): BaseResponseFolderModel
    {
        $this->folderName = $folderName;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->firstName;
    }

    public function setPrenom(?string $firstName): BaseResponseFolderModel
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->lastName;
    }

    public function setNom(?string $lastName): BaseResponseFolderModel
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function toArray(): array
    {
        return [
            FolderEnum::FOLDER_ID => $this->getUserDossierId(),
            FolderEnum::FOLDER => $this->getPartenaireDossierId(),
            FolderEnum::FIRST_NAME => $this->getPrenom(),
            FolderEnum::LAST_NAME => $this->getNom(),
        ];
    }
}
