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
        $multiplicateur = self::getMultiplicateur($documentTypeId);
        if ($multiplicateur == 0) {
            return 0;
        }

        return (int) fmod($status / $multiplicateur, 10);
    }

    private static function getMultiplicateur($type, $getprefix = false)
    {
        $multiplicateur = 0;
        switch ($type):
            case 1:
                $multiplicateur = 1;
                $prefix = 'id';
                break;
            case 2:
                $multiplicateur = 10;
                $prefix = 'iban';
                break;
            case 50:
                $multiplicateur = 100;
                $prefix = 'address';
                break;
            case 56:
                $multiplicateur = 1000;
                $prefix = 'photo_';
                break;
            case 51:
                $multiplicateur = 10000;
                $prefix = 'companyid_';
                break;
            case 53:
                $multiplicateur = 100000;
                $prefix = 'companyarticles_';
                break;
            case 3:
                $multiplicateur = 1000000;
                $prefix = 'impo_';
                break;
            case 4:
                $multiplicateur = 10000000;
                $prefix = 'salaire_';
                break;
            case 59:
                $multiplicateur = 100000000;
                $prefix = 'mobile_';
                break;
            case 60:
                $multiplicateur = 1000000000;
                $prefix = 'signature_';
                break;
            case 57:
                $multiplicateur = 10000000000;
                $prefix = 'facture_';
                break;
            case 58:
                $multiplicateur = 100000000000;
                $prefix = 'releve_';
                break;
            case 61:
                $multiplicateur = 1000000000000;
                $prefix = 'idverso';
                break;
            case 25 :
            case 62:
            case 63:
            case 64:
            case 65:
            case 66:
            case 67:
            case 69:
            case 75:
            case 76:
            default:
                $multiplicateur = 10000000000000;
                $prefix = 'other_';
                break;
            case 99 :
                $prefix = 'docToBeSigned_';
                break;
            case 100:
                $prefix = 'signedDoc_';
                break;
            case 80:
                $multiplicateur = 100000000000000;
                $prefix = 'secondid';
                break;
        endswitch;
        if ($getprefix) {
            return $prefix;
        }

        return $multiplicateur;
    }
}
