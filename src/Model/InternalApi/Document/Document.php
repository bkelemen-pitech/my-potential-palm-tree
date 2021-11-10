<?php

declare(strict_types=1);

namespace App\Model\InternalApi\Document;

use DateTime;
use http\Exception\RuntimeException;

class Document
{
    protected int $documentId;
    protected ?string $nom;
    protected ?int $statut;
    protected ?string $data;
    protected ?DateTime $creation;
    protected ?int $size;
    protected ?string $anomalie;
    protected ?int $visibilite;
    protected ?int $statutVerification;
    protected ?int $statutVerification2;
    protected ?string $partenaireDocumentId;
    protected ?int $masterDocumentId;
    protected ?string $url;
    protected ?string $documentUid;
    protected ?string $signature;
    protected ?bool $cryptage;
    protected ?string $anomalieClient;
    protected ?string $statutVerificationPartenaire;
    protected ?string $signatureInfos;
    protected ?int $documentTypeId;
    protected ?string $type;
    protected ?int $orderDocument;
    protected ?int $obligatoire;
    protected string $documentFile;

    public function getDocumentId(): int
    {
        return $this->documentId;
    }

    public function setDocumentId(int $documentId): Document
    {
        $this->documentId = $documentId;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): Document
    {
        $this->nom = $nom;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(?int $statut): Document
    {
        $this->statut = $statut;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): Document
    {
        $this->data = $data;

        return $this;
    }

    public function getCreation(): ?\DateTime
    {
        return $this->creation;
    }

    public function setCreation(?\DateTime $creation): Document
    {
        $this->creation = $creation;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): Document
    {
        $this->size = $size;

        return $this;
    }

    public function getAnomalie(): ?string
    {
        return $this->anomalie;
    }

    public function setAnomalie(?string $anomalie): Document
    {
        $this->anomalie = $anomalie;

        return $this;
    }

    public function getVisibilite(): ?int
    {
        return $this->visibilite;
    }

    public function setVisibilite(?int $visibilite): Document
    {
        $this->visibilite = $visibilite;

        return $this;
    }

    public function getStatutVerification(): ?int
    {
        return $this->statutVerification;
    }

    public function setStatutVerification(?int $statutVerification): Document
    {
        $this->statutVerification = $statutVerification;

        return $this;
    }

    public function getStatutVerification2(): ?int
    {
        return $this->statutVerification2;
    }

    public function setStatutVerification2(?int $statutVerification2): Document
    {
        $this->statutVerification2 = $statutVerification2;

        return $this;
    }

    public function getPartenaireDocumentId(): ?string
    {
        return $this->partenaireDocumentId;
    }

    public function setPartenaireDocumentId(?string $partenaireDocumentId): Document
    {
        $this->partenaireDocumentId = $partenaireDocumentId;

        return $this;
    }

    public function getMasterDocumentId(): ?int
    {
        return $this->masterDocumentId;
    }

    public function setMasterDocumentId(?int $masterDocumentId): Document
    {
        $this->masterDocumentId = $masterDocumentId;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): Document
    {
        $this->url = $url;

        return $this;
    }

    public function getDocumentUid(): ?string
    {
        return $this->documentUid;
    }

    public function setDocumentUid(?string $documentUid): Document
    {
        $this->documentUid = $documentUid;

        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(?string $signature): Document
    {
        $this->signature = $signature;

        return $this;
    }

    public function getCryptage(): ?bool
    {
        return $this->cryptage;
    }

    public function setCryptage(?bool $cryptage): Document
    {
        $this->cryptage = $cryptage;

        return $this;
    }

    public function getAnomalieClient(): ?string
    {
        return $this->anomalieClient;
    }

    public function setAnomalieClient(?string $anomalieClient): Document
    {
        $this->anomalieClient = $anomalieClient;

        return $this;
    }

    public function getStatutVerificationPartenaire(): ?string
    {
        return $this->statutVerificationPartenaire;
    }

    public function setStatutVerificationPartenaire(?string $statutVerificationPartenaire): Document
    {
        $this->statutVerificationPartenaire = $statutVerificationPartenaire;

        return $this;
    }

    public function getSignatureInfos(): ?string
    {
        return $this->signatureInfos;
    }

    public function setSignatureInfos(?string $signatureInfos): Document
    {
        $this->signatureInfos = $signatureInfos;

        return $this;
    }

    public function getDocumentTypeId(): ?int
    {
        return $this->documentTypeId;
    }

    public function setDocumentTypeId(?int $documentTypeId): Document
    {
        $this->documentTypeId = $documentTypeId;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): Document
    {
        $this->type = $type;

        return $this;
    }

    public function getOrderDocument(): ?int
    {
        return $this->orderDocument;
    }

    public function setOrderDocument(?int $orderDocument): Document
    {
        $this->orderDocument = $orderDocument;

        return $this;
    }

    public function getObligatoire(): ?int
    {
        return $this->obligatoire;
    }

    public function setObligatoire(?int $obligatoire): Document
    {
        $this->obligatoire = $obligatoire;

        return $this;
    }

    public function getDocumentFile(): string
    {
        return $this->documentFile;
    }

    public function setDocumentFile(?string $documentFile): Document
    {
        $decoded = base64_decode($documentFile, true);
        if (false === $decoded) {
            throw new RuntimeException('Invalid base 64 string received.');
        }
        $this->documentFile = $decoded;

        return $this;
    }
}
