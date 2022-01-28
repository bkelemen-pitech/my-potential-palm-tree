<?php

namespace App\Enum;

class AdministratorEnum extends BaseEnum
{
    // API RESPONSE
    public const ADMINISTRATORS = 'administrators';

    public const ADMINISTRATOR_ROLES = [
        UserEnum::USER_ROLE_1,
        UserEnum::USER_ROLE_4,
        UserEnum::USER_ROLE_6,
    ];
}
