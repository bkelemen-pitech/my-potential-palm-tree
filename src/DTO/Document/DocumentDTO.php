<?php

declare(strict_types=1);

namespace App\DTO\Document;

class DocumentDTO
{
    protected int $documentId;
    protected ?string $name;
    protected ?int $status;
    protected ?string $data;
    protected ?\DateTime $creation;
    protected ?int $size;
    protected ?string $anomaly;
    protected ?int $visibility;
    protected ?int $statusVerification;
    protected ?int $statusVerification2;
    protected ?string $partnerDocumentId;
    protected ?int $masterDocumentId;
    protected ?string $url;
    protected ?string $documentUid;
    protected ?string $signature;
    protected ?bool $encryption;
    protected ?string $customerAnomaly;
    protected ?string $partnerVerificationStatus;
    protected ?string $signatureInfos;
    protected ?int $personDocumentId;
    protected ?int $partnerFolderId;
    protected ?int $documentTypeId;
    protected ?string $type;
    protected ?int $orderDocument;
    protected ?int $mandatory;
    protected ?string $content;
    protected ?string $nameVerso;
    protected ?string $contentVerso;

    public function getDocumentId(): int
    {
        return $this->documentId;
    }

    public function setDocumentId(int $documentId): DocumentDTO
    {
        $this->documentId = $documentId;

        return $this;
    }

    public function getDocumentUid(): ?string
    {
        return $this->documentUid;
    }

    public function setDocumentUid(?string $documentUid): DocumentDTO
    {
        $this->documentUid = $documentUid;

        return $this;
    }

    public function getMasterDocumentId(): ?int
    {
        return $this->masterDocumentId;
    }

    public function setMasterDocumentId(?int $masterDocumentId): DocumentDTO
    {
        $this->masterDocumentId = $masterDocumentId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): DocumentDTO
    {
        $this->name = $name;

        return $this;
    }

    public function getStatusVerification(): ?int
    {
        return $this->statusVerification;
    }

    public function setStatusVerification(?int $statusVerification): DocumentDTO
    {
        $this->statusVerification = $statusVerification;

        return $this;
    }

    public function getStatusVerification2(): ?int
    {
        return $this->statusVerification2;
    }

    public function setStatusVerification2(?int $statusVerification2): DocumentDTO
    {
        $this->statusVerification2 = $statusVerification2;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): DocumentDTO
    {
        $this->status = $status;

        return $this;
    }

    public function getCreation(): ?\DateTime
    {
        return $this->creation;
    }

    public function setCreation(?\DateTime $creation): DocumentDTO
    {
        $this->creation = $creation;

        return $this;
    }

    public function getPersonDocumentId(): ?int
    {
        return $this->personDocumentId;
    }

    public function setPersonDocumentId(?int $personDocumentId): DocumentDTO
    {
        $this->personDocumentId = $personDocumentId;

        return $this;
    }

    public function getDocumentTypeId(): ?int
    {
        return $this->documentTypeId;
    }

    public function setDocumentTypeId(?int $documentTypeId): DocumentDTO
    {
        $this->documentTypeId = $documentTypeId;

        return $this;
    }

    public function getEncryption(): ?bool
    {
        return $this->encryption;
    }

    public function setEncryption(?bool $encryption): DocumentDTO
    {
        $this->encryption = $encryption;

        return $this;
    }

    public function getCustomerAnomaly(): ?string
    {
        return $this->customerAnomaly;
    }

    public function setCustomerAnomaly(?string $customerAnomaly): DocumentDTO
    {
        $this->customerAnomaly = $customerAnomaly;

        return $this;
    }

    public function getPartnerVerificationStatus(): ?string
    {
        return $this->partnerVerificationStatus;
    }

    public function setPartnerVerificationStatus(?string $partnerVerificationStatus): DocumentDTO
    {
        $this->partnerVerificationStatus = $partnerVerificationStatus;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): DocumentDTO
    {
        $this->content = $content;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): DocumentDTO
    {
        $this->data = $data;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): DocumentDTO
    {
        $this->size = $size;

        return $this;
    }

    public function getAnomaly(): ?string
    {
        return $this->anomaly;
    }

    public function setAnomaly(?string $anomaly): DocumentDTO
    {
        $this->anomaly = $anomaly;

        return $this;
    }

    public function getVisibility(): ?int
    {
        return $this->visibility;
    }

    public function setVisibility(?int $visibility): DocumentDTO
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getPartnerDocumentId(): ?string
    {
        return $this->partnerDocumentId;
    }

    public function setPartnerDocumentId(?string $partnerDocumentId): DocumentDTO
    {
        $this->partnerDocumentId = $partnerDocumentId;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): DocumentDTO
    {
        $this->url = $url;

        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(?string $signature): DocumentDTO
    {
        $this->signature = $signature;

        return $this;
    }

    public function getSignatureInfos(): ?string
    {
        return $this->signatureInfos;
    }

    public function setSignatureInfos(?string $signatureInfos): DocumentDTO
    {
        $this->signatureInfos = $signatureInfos;

        return $this;
    }

    public function getPartnerFolderId(): ?int
    {
        return $this->partnerFolderId;
    }

    public function setPartnerFolderId(?int $partnerFolderId): DocumentDTO
    {
        $this->partnerFolderId = $partnerFolderId;

        return $this;
    }

    public function getOrderDocument(): ?int
    {
        return $this->orderDocument;
    }

    public function setOrderDocument(?int $orderDocument): DocumentDTO
    {
        $this->orderDocument = $orderDocument;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): DocumentDTO
    {
        $this->type = $type;

        return $this;
    }

    public function getMandatory(): ?int
    {
        return $this->mandatory;
    }

    public function setMandatory(?int $mandatory): DocumentDTO
    {
        $this->mandatory = $mandatory;

        return $this;
    }

    public function getContentVerso(): ?string
    {
        return $this->contentVerso;
    }

    public function setContentVerso(?string $contentVerso): DocumentDTO
    {
        $this->contentVerso = $contentVerso;

        return $this;
    }

    public function getNameVerso(): ?string
    {
        return $this->nameVerso;
    }

    public function setNameVerso(?string $nameVerso): DocumentDTO
    {
        $this->nameVerso = $nameVerso;

        return $this;
    }
}
