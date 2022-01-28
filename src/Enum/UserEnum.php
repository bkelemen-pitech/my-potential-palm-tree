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
    public const ROLE_ADMINISTRATOR = 1;
    public const ROLE_SEIZER = 2;
    public const ROLE_MANAGER = 3;
    public const ROLE_SUPERVISOR = 4;
    public const ROLE_GUEST = 5;
    public const ROLE_AGENT = 6;
    public const ROLE_AGENT_HISTORICAL = 7;
}
