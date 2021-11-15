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

    // Monolith routes params name
    public const DOCUMENT_INCLUDE_FILES = 'include-files';
    public const DOCUMENT_STATUS = 'statut';
    public const DOCUMENT_UID = 'document-uid';
    public const DOCUMENT_VERIFICATION_STATUS2 = 'statut_verification2';

}
