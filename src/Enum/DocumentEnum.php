<?php

namespace App\Enum;

class DocumentEnum
{
    // Document status (statut Monolith value)
    const UNTREATED	= 1; // Non traite
    const TREATED = 2; // Traite
    const ANOMALY = 3; // Anomalie
    const DELETED = 4; // Supprime
    const DOCUMENT_TYPE_DOCUEMENT_A_SIGNE_ELEC	= 99;

    // Document status (value returned based on "statut" and statutVerification2)
    public const PENDING = 'pending';
    public const VALID = 'valid';
    public const INVALID = 'invalid';
}
