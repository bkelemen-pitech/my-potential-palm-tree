<?php

declare(strict_types=1);

namespace App\Model\Request;

use App\Enum\FolderEnum;
use Symfony\Component\Validator\Constraints as Assert;

class DefaultFolderFiltersModel extends BaseFolderFiltersModel
{
    /**
     * @Assert\Type(type="string")
     */
    protected ?string $agencyAd = null;

    public function getAgencyAd(): ?string
    {
        return $this->agencyAd;
    }

    public function setAgencyAd(?string $agencyAd): DefaultFolderFiltersModel
    {
        $this->agencyAd = $agencyAd;

        return $this;
    }

    public function toArray(): array
    {
        $parentFields = parent::toArray();
        $myFields = [
            FolderEnum::AGENCY_AD => $this->getAgencyAd() ? [$this->getAgencyAd()] : null,
        ];

        return array_merge($parentFields, $myFields);
    }
}
