<?php

declare(strict_types=1);

namespace App\Model\Request;

interface PaginationInterface
{
    public function getOrderBy(): ?string;
    public function getOrder(): ?string;
    public function getLimit(): ?string;
    public function getPage(): ?string;
    public function getIdColumnName(): string;
    public function getOrderByChoices(): array;
}
