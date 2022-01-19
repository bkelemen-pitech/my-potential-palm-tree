<?php

declare(strict_types=1);

namespace App\Enum;

class BepremsEnum
{
    // Filters for get persons by folder id
    public const PERSON_ORDER = 'person-order';
    public const PERSON_INFO_ORDER = 'person-info-order';
    public const PERSON_ORDER_BY = 'person-order-by';
    public const PERSON_INFO_ORDER_BY = 'person-info-order-by';

    public const DEFAULT_FOLDER_BY_ID_FILTERS = [
        BepremsEnum::PERSON_ORDER => 'ASC NULLS FIRST',
        BepremsEnum::PERSON_INFO_ORDER => 'ASC',
        BepremsEnum::PERSON_ORDER_BY => 'prenom,nom',
        BepremsEnum::PERSON_INFO_ORDER_BY => 'source,creation'
    ];

    public const APPLICATION = 'application';
    public const LOGIN_APPLICATION = 'backoffice';
}
