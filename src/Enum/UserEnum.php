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
}
