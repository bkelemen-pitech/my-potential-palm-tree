<?php

declare(strict_types=1);

namespace App\Model\InternalApi\Folder;

class FolderById
{
    protected int $userDossierId;
    protected ?string $partenaireDossierId;
    protected ?int $userId;
    protected ?int $statut;
    protected ?int $statutWorkflow;
    protected ?int $agenceIdRef;
    protected ?string $dossier;
    protected ?int $label;
    protected ?int $abonnement;
    protected ?\DateTime $creation;
    protected ?\DateTime $modification;
    protected ?int $statutVerification;
    protected ?int $statutVerification2;
    protected ?string $agenceIdPartenaire = null;
    protected ?string $login = null;

    public function getUserDossierId(): int
    {
        return $this->userDossierId;
    }

    public function setUserDossierId(int $userDossierId): FolderById
    {
        $this->userDossierId = $userDossierId;

        return $this;
    }

    public function getPartenaireDossierId(): ?string
    {
        return $this->partenaireDossierId;
    }

    public function setPartenaireDossierId(?string $partenaireDossierId): FolderById
    {
        $this->partenaireDossierId = $partenaireDossierId;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): FolderById
    {
        $this->userId = $userId;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(?int $statut): FolderById
    {
        $this->statut = $statut;

        return $this;
    }

    public function getStatutWorkflow(): ?int
    {
        return $this->statutWorkflow;
    }

    public function setStatutWorkflow(?int $statutWorkflow): FolderById
    {
        $this->statutWorkflow = $statutWorkflow;

        return $this;
    }

    public function getAgenceIdRef(): ?int
    {
        return $this->agenceIdRef;
    }

    public function setAgenceIdRef(?int $agenceIdRef): FolderById
    {
        $this->agenceIdRef = $agenceIdRef;

        return $this;
    }

    public function getDossier(): ?string
    {
        return $this->dossier;
    }

    public function setDossier(?string $dossier): FolderById
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getLabel(): ?int
    {
        return $this->label;
    }

    public function setLabel(?int $label): FolderById
    {
        $this->label = $label;

        return $this;
    }

    public function getAbonnement(): ?int
    {
        return $this->abonnement;
    }

    public function setAbonnement(?int $abonnement): FolderById
    {
        $this->abonnement = $abonnement;

        return $this;
    }

    public function getCreation(): ?\DateTime
    {
        return $this->creation;
    }

    public function setCreation(?\DateTime $creation): FolderById
    {
        $this->creation = $creation;

        return $this;
    }

    public function getModification(): ?\DateTime
    {
        return $this->modification;
    }

    public function setModification(?\DateTime $modification): FolderById
    {
        $this->modification = $modification;

        return $this;
    }

    public function getStatutVerification(): ?int
    {
        return $this->statutVerification;
    }

    public function setStatutVerification(?int $statutVerification): FolderById
    {
        $this->statutVerification = $statutVerification;

        return $this;
    }

    public function getStatutVerification2(): ?int
    {
        return $this->statutVerification2;
    }

    public function setStatutVerification2(?int $statutVerification2): FolderById
    {
        $this->statutVerification2 = $statutVerification2;

        return $this;
    }

    public function getAgenceIdPartenaire(): ?string
    {
        return $this->agenceIdPartenaire;
    }

    public function setAgenceIdPartenaire(?string $agenceIdPartenaire): FolderById
    {
        $this->agenceIdPartenaire = $agenceIdPartenaire;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): FolderById
    {
        $this->login = $login;

        return $this;
    }
}
