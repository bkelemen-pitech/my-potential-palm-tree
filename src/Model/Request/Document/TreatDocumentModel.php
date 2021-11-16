<?php

declare(strict_types=1);

namespace App\Model\Request\Document;

use Symfony\Component\Validator\Constraints as Assert;

class TreatDocumentModel
{
    /**
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     */
    protected $documentUid;

    /**
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     */
    protected $statusVerification2;

    public function getDocumentUid()
    {
        return $this->documentUid;
    }

    public function setDocumentUid($documentUid): TreatDocumentModel
    {
        $this->documentUid = $documentUid;

        return $this;
    }

    public function getStatusVerification2()
    {
        return $this->statusVerification2;
    }

    public function setStatusVerification2($statusVerification2): TreatDocumentModel
    {
        $this->statusVerification2 = $statusVerification2;

        return $this;
    }
}
