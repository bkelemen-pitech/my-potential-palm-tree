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
    protected ?int $userDossierId = null;

    /**
     * @Assert\Type(type="string")
     */
    protected ?string $partenaireDossierId = '';

    /**
     * @Assert\Type(type="string")
     */
    protected ?string $prenom = '';

    /**
     * @Assert\Type(type="string")
     */
    protected ?string $nom = '';

    public function getUserDossierId(): ?int
    {
        return $this->userDossierId;
    }

    public function setUserDossierId(?int $userDossierId): BaseResponseFolderModel
    {
        $this->userDossierId = $userDossierId;

        return $this;
    }

    public function getPartenaireDossierId(): ?string
    {
        return $this->partenaireDossierId;
    }

    public function setPartenaireDossierId(?string $partenaireDossierId): BaseResponseFolderModel
    {
        $this->partenaireDossierId = $partenaireDossierId;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): BaseResponseFolderModel
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): BaseResponseFolderModel
    {
        $this->nom = $nom;

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
