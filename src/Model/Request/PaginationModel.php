<?php

declare(strict_types=1);

namespace App\Model\Request;

use App\Enum\FolderEnum;
use App\Traits\StringTransformationTrait;
use Symfony\Component\Validator\Constraints as Assert;

class PaginationModel implements PaginationInterface
{
    use StringTransformationTrait;

    public const ID_COLUMN_NAME = 'id';
    public const ORDER_BY_CHOICES = [];

    /**
     * @Assert\Type(type="string")
     * @Assert\Choice(callback="getOrderByChoices")
     */
    protected ?string $orderBy = self::ID_COLUMN_NAME;

    /**
     * @Assert\Choice({"ASC","DESC"})
     */
    protected ?string $order = FolderEnum::DEFAULT_ORDER;

    /**
     * @Assert\Type(type="digit")
     */
    protected ?string $limit = FolderEnum::DEFAULT_LIMIT;

    /**
     * @Assert\Type(type="digit")
     */
    protected ?string $page = FolderEnum::DEFAULT_PAGE;

    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    public function setOrderBy(?string $orderBy): self
    {
        $this->orderBy = $this->snakeToCamel($orderBy);

        return $this;
    }

    public function getOrder(): ?string
    {
        return $this->order;
    }

    public function setOrder(?string $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getLimit(): ?string
    {
        return $this->limit;
    }

    public function setLimit(?string $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage(?string $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getIdColumnName(): string
    {
        return static::ID_COLUMN_NAME;
    }

    public function getOrderByChoices(): array
    {
        return static::ORDER_BY_CHOICES;
    }
}
