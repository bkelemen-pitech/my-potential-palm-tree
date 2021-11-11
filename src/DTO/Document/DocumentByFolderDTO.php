<?php

declare(strict_types=1);

namespace App\DTO\Document;

class DocumentByFolderDTO
{
    protected int $documentId;
    protected ?string $documentUid;
    protected ?int $masterDocumentId;
    protected ?string $name;
    protected ?int $statusVerification;
    protected ?int $statusVerification2;
    protected ?int $status;
    protected ?\DateTime $creation;
    protected ?\DateTime $modification;
    protected ?string $comment;
    protected int $personId;
    protected ?int $personVerification;
    protected ?int $documentTypeId;

    /**
     * @var DocumentByFolderDTO[]
     */
    protected array $slaves = [];

    public function getDocumentId(): int
    {
        return $this->documentId;
    }

    public function setDocumentId(int $documentId): DocumentByFolderDTO
    {
        $this->documentId = $documentId;

        return $this;
    }

    public function getDocumentUid(): ?string
    {
        return $this->documentUid;
    }

    public function setDocumentUid(?string $documentUid): DocumentByFolderDTO
    {
        $this->documentUid = $documentUid;

        return $this;
    }

    public function getMasterDocumentId(): ?int
    {
        return $this->masterDocumentId;
    }

    public function setMasterDocumentId(?int $masterDocumentId): DocumentByFolderDTO
    {
        $this->masterDocumentId = $masterDocumentId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): DocumentByFolderDTO
    {
        $this->name = $name;

        return $this;
    }

    public function getStatusVerification(): ?int
    {
        return $this->statusVerification;
    }

    public function setStatusVerification(?int $statusVerification): DocumentByFolderDTO
    {
        $this->statusVerification = $statusVerification;

        return $this;
    }

    public function getStatusVerification2(): ?int
    {
        return $this->statusVerification2;
    }

    public function setStatusVerification2(?int $statusVerification2): DocumentByFolderDTO
    {
        $this->statusVerification2 = $statusVerification2;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): DocumentByFolderDTO
    {
        $this->status = $status;

        return $this;
    }

    public function getCreation(): ?\DateTime
    {
        return $this->creation;
    }

    public function setCreation(?\DateTime $creation): DocumentByFolderDTO
    {
        $this->creation = $creation;

        return $this;
    }

    public function getModification(): ?\DateTime
    {
        return $this->modification;
    }

    public function setModification(?\DateTime $modification): DocumentByFolderDTO
    {
        $this->modification = $modification;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): DocumentByFolderDTO
    {
        $this->comment = $comment;

        return $this;
    }

    public function getPersonId(): int
    {
        return $this->personId;
    }

    public function setPersonId(int $personId): DocumentByFolderDTO
    {
        $this->personId = $personId;

        return $this;
    }

    public function getPersonVerification(): ?int
    {
        return $this->personVerification;
    }

    public function setPersonVerification(?int $personVerification): DocumentByFolderDTO
    {
        $this->personVerification = $personVerification;

        return $this;
    }

    public function getDocumentTypeId(): ?int
    {
        return $this->documentTypeId;
    }

    public function setDocumentTypeId(?int $documentTypeId): DocumentByFolderDTO
    {
        $this->documentTypeId = $documentTypeId;

        return $this;
    }

    /**
     * @return DocumentByFolderDTO[]
     */
    public function getSlaves(): array
    {
        return $this->slaves;
    }

    /**
     * @param DocumentByFolderDTO[] $slaves
     */
    public function setSlaves(array $slaves): DocumentByFolderDTO
    {
        $this->slaves = $slaves;

        return $this;
    }
}
