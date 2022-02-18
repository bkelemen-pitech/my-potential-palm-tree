<?php

declare(strict_types=1);

namespace App\Model\Request;

use App\Enum\FolderEnum;
use Symfony\Component\Validator\Constraints as Assert;
use Kyc\InternalApiBundle\Model\Request\Folder\FolderFiltersModel as KycFolderFiltersModel;

class BaseFolderFiltersModel extends KycFolderFiltersModel
{
    public const ID_COLUMN_NAME = FolderEnum::PARTNER_FOLDER_ID_FR;
    public const ORDER_BY_CHOICES = [
        FolderEnum::UPDATED_AT,
        FolderEnum::CREATED_AT,
        FolderEnum::PARTNER_FOLDER_ID,
        FolderEnum::PERSON_FIRST_NAME,
        FolderEnum::PERSON_LAST_NAME,
        FolderEnum::PERSON_EMAIL,
        FolderEnum::PERSON_TELEPHONE,
        FolderEnum::LABEL,
        FolderEnum::WORKFLOW_STATUS,
        FolderEnum::USER_FOLDER_ID,
    ];

    /**
     * @Assert\Type(type="string")
     * @Assert\Choice(callback="getOrderByChoices")
     */
    protected ?string $orderBy = FolderEnum::PROCESSING_VALUE;
}
