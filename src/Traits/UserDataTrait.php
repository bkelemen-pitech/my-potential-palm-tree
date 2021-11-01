<?php

declare(strict_types=1);

namespace App\Traits;

use App\DTO\UserDataDTO;
use App\Security\User;

trait UserDataTrait
{
    public function getUserData(): UserDataDTO
    {
        $userData = new UserDataDTO();
        /** @var User $user */
        $user = $this->getUser();
        $roles = $user->getRoles();

        return $userData
            ->setAgentId($user->getAgentId())
            ->setAgencyId($user->getAgencyId())
            ->setAgencyMainPersonType($user->getAgencyMainPersonType())
            ->setPartnerCode($user->getPartnerCode())
            ->setRole((int) reset($roles))
            ->setLang($user->getLang());
    }
}
