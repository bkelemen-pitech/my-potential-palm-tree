<?php

namespace App\Enum;

class DocumentEnum
{
    // Document status (statut Monolith value)
    const UNTREATED = 1; // Non traite
    const TREATED = 2; // Traite
    const ANOMALY = 3; // Anomalie
    const DELETED = 4; // Supprime

    // Document status (value returned based on "statut" and statutVerification2)
    public const INVALID = 'invalid';
    public const PENDING = 'pending';
    public const VALID = 'valid';

    // Document types
    const DOCUMENT_TYPE_DOCUEMENT_A_SIGNE_ELEC = 99;

    // API Params
    const INCLUDE_FILES_PARAM = 'include_files';

    // API Response
    const DOCUMENT_ID = 'documentId';
    const DOCUMENT_NAME = 'name';
    const DOCUMENT_PERSON_ID = 'personId';
    const DOCUMENT_STATUS = 'status';
    const DOCUMENT_TYPE = 'type';
    const DOCUMENT_UID = 'uid';
    const DOCUMENT_VERIFICATION_STATUS = 'documentStatus';

    // Monolith routes params name
    public const BEPREMS_DOCUMENT_UID = 'document_uid';

    public const DEFAULT_MULTIPLIER = 10000000000000;
    public const MULTIPLIER = [
        1 => 1,
        2 => 10,
        3 => 1000000,
        4 => 10000000,
        50 => 100,
        51 => 10000,
        53 => 100000,
        56 => 1000,
        57 => 10000000000,
        58 => 100000000000,
        59 => 100000000,
        60 => 1000000000,
        61 => 1000000000000,
        80 => 100000000000000,
        99 => 0,
        100 => 0,
    ];
}
