<?php

declare(strict_types=1);

namespace App\DTO\Person;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class PersonInfoDTO
{
    /**
     * @SerializedName("name_info")
     *
     * @Groups({"readList"})
     */
    protected ?string $nameInfo;

    /**
     * @SerializedName("data_info")
     *
     * @Groups({"readList"})
     */
    protected ?string $dataInfo;

    /**
     * @Groups({"readList"})
     */
    protected ?string $source;

    public function getNameInfo(): ?string
    {
        return $this->nameInfo;
    }

    public function setNameInfo(?string $nameInfo): PersonInfoDTO
    {
        $this->nameInfo = $nameInfo;

        return $this;
    }

    public function getDataInfo(): ?string
    {
        return $this->dataInfo;
    }

    public function setDataInfo(?string $dataInfo): PersonInfoDTO
    {
        $this->dataInfo = $dataInfo;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): PersonInfoDTO
    {
        $this->source = $source;

        return $this;
    }
}
