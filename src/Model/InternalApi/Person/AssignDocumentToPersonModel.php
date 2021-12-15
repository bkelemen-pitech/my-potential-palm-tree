<?php

declare(strict_types=1);

namespace App\Model\InternalApi\Person;

use App\Enum\DocumentEnum;
use App\Enum\FolderEnum;
use App\Enum\PersonEnum;
use Symfony\Component\Validator\Constraints as Assert;

class AssignDocumentToPersonModel
{
    /**
     * @Assert\Type(type="integer")
     */
    protected $folderId = null;

    /**
     * @Assert\Type(type="string")
     */
    protected $personUid = null;

    /**
     * @Assert\Type(type="string")
     */
    protected $documentUid = null;

    public function getFolderId()
    {
        return $this->folderId;
    }

    public function setFolderId($folderId): AssignDocumentToPersonModel
    {
        $this->folderId = $folderId;

        return $this;
    }

    public function getPersonUid()
    {
        return $this->personUid;
    }

    public function setPersonUid($personUid): AssignDocumentToPersonModel
    {
        $this->personUid = $personUid;

        return $this;
    }

    public function getDocumentUid()
    {
        return $this->documentUid;
    }

    public function setDocumentUid($documentUid): AssignDocumentToPersonModel
    {
        $this->documentUid = $documentUid;

        return $this;
    }

    public function toArray(): array
    {
        $data = [
            DocumentEnum::BEPREMS_DOCUMENT_UID => $this->documentUid,
            PersonEnum::BEPREMS_PERSON_UID => $this->personUid,
            FolderEnum::USER_FOLDER_ID_FR => $this->folderId,
        ];

        return array_filter($data, function ($field) { return !is_null($field);});
    }
}
