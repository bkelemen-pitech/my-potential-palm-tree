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

    // API Response
    const DOCUMENT_ID = 'documentId';
    const DOCUMENT_NAME = 'name';
    const DOCUMENT_PERSON_ID = 'personId';
    const DOCUMENT_STATUS = 'status';
    const DOCUMENT_TYPE = 'type';
    const DOCUMENT_UID = 'uid';
    const DOCUMENT_VERIFICATION_STATUS = 'documentStatus';
}
