<?php

declare(strict_types=1);

namespace App\Tests\Mocks\Data;

class FolderData
{
    public const GET_FOLDER_BY_ID_ORDER_FILTERS = [
        'person-order' => 'ASC NULLS FIRST',
        'person-info-order' => 'ASC',
        'person-order-by' => 'prenom,nom',
        'person-info-order-by' => 'source,creation'
    ];
}
