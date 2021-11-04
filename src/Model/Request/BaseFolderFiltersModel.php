<?php

declare(strict_types=1);

namespace App\Model\Request;

use App\Enum\FolderEnum;
use App\Traits\StringTransformationTrait;
use Symfony\Component\Validator\Constraints as Assert;

class BaseFolderFiltersModel extends PaginationModel implements BaseFolderFiltersInterface
{
    use StringTransformationTrait;

    public const ID_COLUMN_NAME = FolderEnum::PARTNER_FOLDER_ID_FR;
    public const ORDER_BY_CHOICES = [
        FolderEnum::UPDATED_AT,
        FolderEnum::CREATED_AT,
        FolderEnum::PARTNER_FOLDER_ID,
        FolderEnum::FIRST_NAME,
        FolderEnum::LAST_NAME,
        FolderEnum::EMAIL,
        FolderEnum::TELEPHONE,
        FolderEnum::LABEL,
        FolderEnum::WORKFLOW_STATUS,
        FolderEnum::USER_FOLDER_ID,
    ];

    /**
     * @Assert\Type(type="string")
     */
    protected ?string $orderBy = FolderEnum::UPDATED_AT;

    /**
     * @Assert\Type(type="string")
     */
    protected ?string $textSearch = null;

    /**
     * @Assert\Type(type="string")
     */
    protected ?string $partnerFolderId = '';

    /**
     * @Assert\Type(type="string")
     */
    protected ?string $firstName = '';

    /**
     * @Assert\Type(type="string")
     */
    protected ?string $lastName = '';

    /**
     * @Assert\Type(type="string")
     */
    protected ?string $email = '';

    /**
     * @Assert\Type(type="string")
     */
    protected ?string $telephone = '';

    /**
     * @Assert\Type("datetime")
     */
    protected ?\DateTime $startDate = null;

    /**
     * @Assert\Type("datetime")
     */
    protected ?\DateTime $endDate = null;

    /**
     * @Assert\Type(type="string")
     */
    protected ?string $textSearchFields = null;

    /**
     * @Assert\Type(type="string")
     */
    protected ?string $filters = null;

    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    public function setOrderBy(?string $orderBy): BaseFolderFiltersModel
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    public function getTextSearch(): ?string
    {
        return $this->textSearch;
    }

    public function setTextSearch(?string $textSearch): BaseFolderFiltersModel
    {
        $this->textSearch = $textSearch;

        return $this;
    }

    public function getPartnerFolderId(): ?string
    {
        return $this->partnerFolderId;
    }

    public function setPartnerFolderId(?string $partnerFolderId): BaseFolderFiltersModel
    {
        $this->partnerFolderId = $partnerFolderId;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): BaseFolderFiltersModel
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): BaseFolderFiltersModel
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): BaseFolderFiltersModel
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): BaseFolderFiltersModel
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?string $startDate): BaseFolderFiltersModel
    {
        $this->startDate = $startDate ? (new \DateTime($startDate)) : null;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?string $endDate): BaseFolderFiltersModel
    {
        $this->endDate = $endDate ? (new \DateTime($endDate)) : null;

        return $this;
    }

    public function getTextSearchFields(): ?string
    {
        return $this->textSearchFields;
    }

    public function setTextSearchFields(?string $textSearchFields): BaseFolderFiltersModel
    {
        $this->textSearchFields = $textSearchFields;

        return $this;
    }

    public function getFilters(): ?string
    {
        return $this->filters;
    }

    public function setFilters(?string $filters): BaseFolderFiltersModel
    {
        $this->filters = $filters;

        return $this;
    }

    public function getListCriteria(): array
    {
        return [
            FolderEnum::PARTNER_FOLDER_ID_FR => $this->partnerFolderId,
            FolderEnum::LAST_NAME_FR => $this->lastName,
            FolderEnum::FIRST_NAME_FR => $this->firstName,
            FolderEnum::EMAIL => $this->email,
            FolderEnum::TELEPHONE => $this->telephone,
        ];
    }

    public function getListOrderBy(): array
    {
        return [
            $this->getOrderBy() => $this->getOrder(),
        ];
    }

    protected function prepareTextSearchField(
        array $keys,
        array $criteria,
        ?string $prefix = null
    ): array {
        $final = [];
        $useCriteria = count($criteria) > 0;

        foreach ($keys as $key) {
            $key = $this->snakeToCamel($key);

            if ($useCriteria && !in_array($key, $criteria)) {
                continue;
            }
            $key = $prefix . $this->camelToSnake($key);
            $final[] = $key;
        }

        return $final;
    }

    private function prepareTextSearchFields(): array
    {
        $textSearchFields = str_replace(' ', '', $this->getTextSearchFields());
        $folderKeys = [FolderEnum::PARTNER_FOLDER_ID_FR];
        $personKeys = [FolderEnum::LAST_NAME_FR, FolderEnum::FIRST_NAME_FR, FolderEnum::EMAIL, FolderEnum::TELEPHONE];
        $fields = explode(',', $textSearchFields);
        $fields = array_filter($fields, function ($item) {
            return $item != '';
        });

        if (!count($fields)) {
            $userFolderFields = $this->prepareTextSearchField($folderKeys, [], FolderEnum::USER_FOLDER_PREFIX);
            $personFields = $this->prepareTextSearchField($personKeys, [], FolderEnum::PERSON_PREFIX);

            return array_merge($userFolderFields, $personFields);
        }

        $userFolderFields = $this->prepareTextSearchField($fields, $folderKeys, FolderEnum::USER_FOLDER_PREFIX);
        $personFields = $this->prepareTextSearchField($fields, $personKeys, FolderEnum::PERSON_PREFIX);

        return array_merge($userFolderFields, $personFields);
    }

    public function toArray(): array
    {
        return [
            FolderEnum::LIMIT => (int) $this->getLimit(),
            FolderEnum::PAGE => (int) $this->getPage(),
            FolderEnum::ORDER_BY => $this->getOrderBy(),
            FolderEnum::ORDER => $this->getOrder(),
            FolderEnum::TEXT_SEARCH => $this->getTextSearch() ?? null,
            FolderEnum::TEXT_SEARCH_FIELDS => $this->getTextSearch() ? $this->prepareTextSearchFields() : null,
            FolderEnum::FILTERS => $this->getFilters() ?? null,
        ];
    }
}
