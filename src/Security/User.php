<?php

namespace App\Security;

use App\Enum\UserEnum;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements JWTUserInterface
{
    private $username;

    private $roles = [];

    private $userId;

    /**
     * @var string The hashed password
     */
    private $password;

    public function __construct($username, $password, array $roles, $userId)
    {
        $this->username = $username;
        $this->password = $password;
        $this->roles = $roles;
        $this->userId = $userId;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }

    public static function createFromPayload($username, array $payload)
    {
        return new self(
            $username,
            $payload[UserEnum::PASSWORD] ?? null,
            $payload[UserEnum::USER_ROLES],
            $payload[UserEnum::USER_ID],
        );
    }
}
