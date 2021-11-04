<?php

declare(strict_types=1);

namespace App\Enum;

class BepremsEnum
{
    public const API_DOC_TYPE_ID_DEFAULT_ACTION = 'upload';
    public const API_DOC_TYPE_ID_ACTIONS = [
        1 => 'idcontrol',
        2 => 'ibanflash',
        3 => 'impocontrol',
        50 => 'addresscontrol',
        51 => 'companyid',
        61 => 'idverso',
    ];

    public const SOURCE_COLLECTION_ROUTE = 'PARCOURS';

    public const PARTNER_FOLDER_ID = 'partenaireDossierId';
    public const PARTNER_AGENCY_ID = 'agenceIdPartenaire';
    public const TYPE_DOCUMENT = 'typeDocument';
    public const MANDATORY_FIELDS_FOR_UPLOAD_DOCS = [
        self::PARTNER_FOLDER_ID => self::PARTNER_FOLDER_ID,
        self::PARTNER_AGENCY_ID => self::PARTNER_AGENCY_ID,
    ];

    public const BASE_URL = 'baseurl';
    public const LOGIN = 'login';
    public const PASSWORD = 'password';
    public const USERNAME = 'username';
    public const PARTNER_CODE = 'partner_code';
    public const APPLICATION = 'application';
    public const LOGIN_APPLICATION = 'middleoffice';

    public const AUTHENTICATION_REQUIRED_PARAMS = [
        self::BASE_URL,
        self::LOGIN,
        self::PASSWORD,
    ];

    // Filters for get persons by folder id
    public const PERSON_ORDER = 'person-order';
    public const PERSON_INFO_ORDER = 'person-info-order';
    public const PERSON_ORDER_BY = 'person-order-by';
    public const PERSON_INFO_ORDER_BY = 'person-info-order-by';
}
