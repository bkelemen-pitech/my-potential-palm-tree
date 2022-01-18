<?php

declare(strict_types=1);

namespace App\DTO\User;

class UserLoginDTO
{
    protected string $login;
    protected string $password;
    protected int $role;
    protected int $administratorId;

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): UserLoginDTO
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): UserLoginDTO
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    public function setRole(int $role): UserLoginDTO
    {
        $this->role = $role;

        return $this;
    }

    public function getAdministratorId(): int
    {
        return $this->administratorId;
    }

    public function setAdministratorId(int $administratorId): UserLoginDTO
    {
        $this->administratorId = $administratorId;

        return $this;
    }
}
