<?php

namespace App\Enum;

class AdministrationEnum
{
    // API PARAMETERS
    public const ORDER = 'order';
    public const PAGE = 'page';

    // API RESPONSE
    public const ADMINISTRATORS = 'administrators';

    // ADMINISTRATOR ROLES
    public const ADMINISTRATOR_ROLE_1 = 1;
    public const ADMINISTRATOR_ROLE_2 = 2;
    public const ADMINISTRATOR_ROLE_3 = 3;
    public const ADMINISTRATOR_ROLE_4 = 4;
    public const ADMINISTRATOR_ROLE_5 = 5;
    public const ADMINISTRATOR_ROLE_6 = 6;
    public const ADMINISTRATOR_ROLE_7 = 7;

    public const ADMINISTRATOR_ROLES = [
        self::ADMINISTRATOR_ROLE_1,
        self::ADMINISTRATOR_ROLE_4,
        self::ADMINISTRATOR_ROLE_6,
    ];
}
