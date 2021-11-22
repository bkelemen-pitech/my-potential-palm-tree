<?php

declare(strict_types=1);

namespace App\Helper;

use App\DTO\Document\DocumentByFolderDTO;
use App\Enum\DocumentEnum;
use App\Enum\VerificationStatusEnum;

class DocumentHelper
{
    public static function isPending(DocumentByFolderDTO $document): bool
    {
        return DocumentEnum::UNTREATED === $document->getStatus();
    }

    public static function isValid(DocumentByFolderDTO $document): bool
    {
        if (DocumentEnum::TREATED === $document->getStatus() && $document->getStatusVerification() >= VerificationStatusEnum::VERIFIED_NOT_ELIGIBLE) {
            return true;
        }

        $verificationStatus2 = self::getStatutVerification(
            $document->getStatusVerification2(),
            $document->getDocumentTypeId()
        );

        return DocumentEnum::TREATED === $document->getStatus() && $verificationStatus2 >= VerificationStatusEnum::VERIFIED_NOT_ELIGIBLE;
    }

    public static function isInvalid(DocumentByFolderDTO $document, int $verificationStatus): bool
    {
        return (DocumentEnum::TREATED === $document->getStatus() || DocumentEnum::ANOMALY === $document->getStatus())
            && DocumentEnum::DOCUMENT_TYPE_DOCUEMENT_A_SIGNE_ELEC !== $document->getDocumentTypeId()
            && $verificationStatus < VerificationStatusEnum::VERIFIED_NOT_ELIGIBLE;
    }

    public static function getStatutVerification($status, $documentTypeId): int
    {
        $multiplier = self::getMultiplier($documentTypeId);
        if ($multiplier == 0) {
            return 0;
        }

        return (int) fmod($status / $multiplier, 10);
    }

    private static function getMultiplier($type)
    {
        return DocumentEnum::MULTIPLIER[$type] ?? DocumentEnum::DEFAULT_MULTIPLIER;
    }
}
