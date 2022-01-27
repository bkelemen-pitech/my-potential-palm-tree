<?php

declare(strict_types=1);

namespace App\Model\Request;

use App\Enum\AdministrationEnum;
use Kyc\InternalApiBundle\Model\Request\Administrator\AdministratorFilterModel as KycAdministratorFiltersModel;

class AdministratorModel extends KycAdministratorFiltersModel
{
    protected ?array $roles = AdministrationEnum::ADMINISTRATOR_ROLES;
}
