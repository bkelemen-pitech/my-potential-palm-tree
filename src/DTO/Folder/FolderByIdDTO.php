<?php

declare(strict_types=1);

namespace App\DTO\Folder;

use App\DTO\Person\PersonDTO;
use Symfony\Component\Serializer\Annotation\SerializedName;

class FolderByIdDTO
{
    protected int $id;
    protected ?string $partnerFolderId;
    protected ?int $status;
    protected ?int $workflowStatus;
    protected ?int $label;
    protected ?int $subscription;
    /**
     * @var PersonDTO[]
     */
    protected array $persons = [];
    protected ?string $partnerAgencyId;
    protected ?string $login;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): FolderByIdDTO
    {
        $this->id = $id;

        return $this;
    }

    public function getPartnerFolderId(): ?string
    {
        return $this->partnerFolderId;
    }

    public function setPartnerFolderId(?string $partnerFolderId): FolderByIdDTO
    {
        $this->partnerFolderId = $partnerFolderId;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): FolderByIdDTO
    {
        $this->status = $status;

        return $this;
    }

    public function getWorkflowStatus(): ?int
    {
        return $this->workflowStatus;
    }

    public function setWorkflowStatus(?int $workflowStatus): FolderByIdDTO
    {
        $this->workflowStatus = $workflowStatus;

        return $this;
    }

    public function getLabel(): ?int
    {
        return $this->label;
    }

    public function setLabel(?int $label): FolderByIdDTO
    {
        $this->label = $label;

        return $this;
    }

    public function getSubscription(): ?int
    {
        return $this->subscription;
    }

    public function setSubscription(?int $subscription): FolderByIdDTO
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * @return PersonDTO[]
     */
    public function getPersons(): array
    {
        return $this->persons;
    }

    /**
     * @param PersonDTO[] $persons
     */
    public function setPersons(array $persons): FolderByIdDTO
    {
        $this->persons = $persons;

        return $this;
    }

    public function getPartnerAgencyId(): ?string
    {
        return $this->partnerAgencyId;
    }

    public function setPartnerAgencyId(?string $partnerAgencyId): FolderByIdDTO
    {
        $this->partnerAgencyId = $partnerAgencyId;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): FolderByIdDTO
    {
        $this->login = $login;

        return $this;
    }
}
