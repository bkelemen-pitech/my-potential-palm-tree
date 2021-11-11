<?php

declare(strict_types=1);

namespace App\Model\InternalApi\Document;

use DateTime;
use Symfony\Component\Serializer\Annotation\SerializedName;

class DocumentByFolder
{
    protected int $documentId;
    protected ?string $documentUid;
    protected ?int $masterDocumentId;
    /**
     * @SerializedName("name")
     */
    protected ?string $nom;
    /**
     * @SerializedName("statusVerification")
     */
    protected ?int $statutVerification;
    /**
     * @SerializedName("statusVerification2")
     */
    protected ?int $statutVerification2;
    /**
     * @SerializedName("status")
     */
    protected ?int $statut;
    protected ?DateTime $creation;
    protected ?DateTime $modification;
    /**
     * @SerializedName("personId")
     */
    protected int $personneId;
    protected ?int $personVerification;
    protected ?int $documentTypeId;

    public function getDocumentId(): int
    {
        return $this->documentId;
    }

    public function setDocumentId(int $documentId): DocumentByFolder
    {
        $this->documentId = $documentId;

        return $this;
    }

    public function getDocumentUid(): ?string
    {
        return $this->documentUid;
    }

    public function setDocumentUid(?string $documentUid): DocumentByFolder
    {
        $this->documentUid = $documentUid;

        return $this;
    }

    public function getMasterDocumentId(): ?int
    {
        return $this->masterDocumentId;
    }

    public function setMasterDocumentId(?int $masterDocumentId): DocumentByFolder
    {
        $this->masterDocumentId = $masterDocumentId;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): DocumentByFolder
    {
        $this->nom = $nom;

        return $this;
    }

    public function getStatutVerification(): ?int
    {
        return $this->statutVerification;
    }

    public function setStatutVerification(?int $statutVerification): DocumentByFolder
    {
        $this->statutVerification = $statutVerification;

        return $this;
    }

    public function getStatutVerification2(): ?int
    {
        return $this->statutVerification2;
    }

    public function setStatutVerification2(?int $statutVerification2): DocumentByFolder
    {
        $this->statutVerification2 = $statutVerification2;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(?int $statut): DocumentByFolder
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCreation(): ?DateTime
    {
        return $this->creation;
    }

    public function setCreation(?DateTime $creation): DocumentByFolder
    {
        $this->creation = $creation;

        return $this;
    }

    public function getModification(): ?DateTime
    {
        return $this->modification;
    }

    public function setModification(?DateTime $modification): DocumentByFolder
    {
        $this->modification = $modification;

        return $this;
    }

    public function getPersonneId(): int
    {
        return $this->personneId;
    }

    public function setPersonneId(int $personneId): DocumentByFolder
    {
        $this->personneId = $personneId;

        return $this;
    }

    public function getPersonVerification(): ?int
    {
        return $this->personVerification;
    }

    public function setPersonVerification(?int $personVerification): DocumentByFolder
    {
        $this->personVerification = $personVerification;

        return $this;
    }

    public function getDocumentTypeId(): ?int
    {
        return $this->documentTypeId;
    }

    public function setDocumentTypeId(?int $documentTypeId): DocumentByFolder
    {
        $this->documentTypeId = $documentTypeId;

        return $this;
    }
}
