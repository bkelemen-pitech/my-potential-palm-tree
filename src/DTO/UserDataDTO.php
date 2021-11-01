<?php

declare(strict_types=1);

namespace App\DTO;

class UserDataDTO
{
    protected int $agentId;
    protected int $role;
    protected int $agencyId;
    protected string $partnerCode;
    protected ?string $lang = null;
    protected int $agencyMainPersonType;

    public function getAgentId(): int
    {
        return $this->agentId;
    }

    public function setAgentId(int $agentId): UserDataDTO
    {
        $this->agentId = $agentId;

        return $this;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    public function setRole(int $role): UserDataDTO
    {
        $this->role = $role;

        return $this;
    }

    public function getAgencyId(): int
    {
        return $this->agencyId;
    }

    public function setAgencyId(int $agencyId): UserDataDTO
    {
        $this->agencyId = $agencyId;

        return $this;
    }

    public function getPartnerCode(): string
    {
        return $this->partnerCode;
    }

    public function setPartnerCode(string $partnerCode): UserDataDTO
    {
        $this->partnerCode = $partnerCode;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(?string $lang): UserDataDTO
    {
        $this->lang = $lang;

        return $this;
    }

    public function getAgencyMainPersonType(): int
    {
        return $this->agencyMainPersonType;
    }

    public function setAgencyMainPersonType(int $agencyMainPersonType): UserDataDTO
    {
        $this->agencyMainPersonType = $agencyMainPersonType;

        return $this;
    }
}
