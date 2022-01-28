<?php

declare(strict_types=1);

namespace App\Enum;

class UserEnum
{
    public const USER_ID = 'userId';
    public const USER_ROLES = 'roles';

    public const BO_LOGIN_ALLOWED_ROLES = ['ROLE_1', 'ROLE_4', 'ROLE_6'];
    public const PASSWORD = 'password';
    public const USERNAME = 'username';
    public const LOGIN = 'login';

    // ROLES
    public const USER_ROLE_1 = 1;
    public const USER_ROLE_2 = 2;
    public const USER_ROLE_3 = 3;
    public const USER_ROLE_4 = 4;
    public const USER_ROLE_5 = 5;
    public const USER_ROLE_6 = 6;
    public const USER_ROLE_7 = 7;
}
