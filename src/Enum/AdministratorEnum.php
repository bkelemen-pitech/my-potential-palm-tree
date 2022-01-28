<?php

namespace App\Enum;

class AdministratorEnum extends BaseEnum
{
    // API RESPONSE
    public const ADMINISTRATORS = 'administrators';

    public const ADMINISTRATOR_ROLES = [
        UserEnum::ROLE_ADMINISTRATOR,
        UserEnum::ROLE_SUPERVISOR,
        UserEnum::ROLE_AGENT,
    ];
}
