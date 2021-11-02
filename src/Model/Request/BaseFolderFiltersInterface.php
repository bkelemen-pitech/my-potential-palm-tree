<?php

declare(strict_types=1);

namespace App\Model\Request;

interface BaseFolderFiltersInterface extends ListInterface
{
    public function getOrderBy(): ?string;
    public function getTextSearch(): ?string;
    public function getPartnerFolderId(): ?string;
    public function getFirstName(): ?string;
    public function getLastName(): ?string;
    public function getEmail(): ?string;
    public function getTelephone(): ?string;
    public function getStartDate(): ?\DateTime;
    public function getEndDate(): ?\DateTime;
    public function getTextSearchFields(): ?string;
}
