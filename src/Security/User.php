<?php

declare(strict_types=1);

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

final class User implements JWTUserInterface
{
    public const DEFAULT_LANG = 'fr';

    protected ?string $username;
    protected ?string $password;
    protected array $roles;
    protected array $permissions;
    protected int $agencyMainPersonType;
    protected ?string $lang;
    protected ?string $partnerCode;
    protected int $agencyId;
    protected int $agentId;

    public function __construct($username, $password, array $roles, $agentId, $agencyId, $partnerCode, $lang, $agencyMainPersonType,  array $permissions = [])
    {
        $this->username = $username;
        $this->password = $password;
        $this->roles = $roles;
        $this->agentId = $agentId;
        $this->agencyId = $agencyId;
        $this->partnerCode = $partnerCode;
        $this->lang = $lang;
        $this->agencyMainPersonType = $agencyMainPersonType;
        $this->permissions = $permissions;
    }

    public function getAgencyMainPersonType(): int
    {
        return $this->agencyMainPersonType;
    }

    public function getLang(): ?string
    {
        return $this->lang ?? self::DEFAULT_LANG;
    }

    public function getPartnerCode(): ?string
    {
        return $this->partnerCode;
    }

    public function getAgencyId(): int
    {
        return $this->agencyId;
    }

    public function getAgentId(): int
    {
        return $this->agentId;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function setAgencyId(int $agencyId): User
    {
        $this->agencyId = $agencyId;

        return $this;
    }

    public function setLang(?string $lang): User
    {
        $this->lang = $lang;

        return $this;
    }

    public static function createFromPayload($username, array $payload)
    {
        return new self(
            $username,
            $payload['password'] ?? null,
            $payload['roles'],
            $payload['agent_id'],
            $payload['agency_id'],
            $payload['partner_code'],
            $payload['lang'],
            $payload['agency_main_person_type'],
            $payload['permissions'] ?? []
        );
    }
}
