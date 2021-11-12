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

    // Get document details params
    public const DOCUMENT_UID_PARAM = 'document-uid';
    public const DOCUMENT_INCLUDE_FILES_PARAM = 'include-files';
}
