<?php

declare(strict_types=1);

namespace App\Model\Request;

interface ListInterface extends PaginationInterface
{
    public function getListCriteria(): array;
    public function getListOrderBy(): array;
}
