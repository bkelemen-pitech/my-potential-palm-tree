<?php

declare(strict_types=1);

namespace App\Enum;

class AdministratorEnum extends BaseEnum
{
    public const ADMINISTRATOR_ID = 'administrator-id';

    // API RESPONSE
    public const ADMINISTRATORS = 'administrators';

    // ADMINISTRATORS PROPERTY
    public const ADMINISTRATOR_ID_CAMEL_CASE = 'administratorId';

    public const ADMINISTRATOR_ROLES = [
        UserEnum::ROLE_ADMINISTRATOR,
        UserEnum::ROLE_SUPERVISOR,
        UserEnum::ROLE_AGENT,
    ];
}
