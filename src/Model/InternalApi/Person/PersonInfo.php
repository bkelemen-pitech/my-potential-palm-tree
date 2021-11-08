<?php

declare(strict_types=1);

namespace App\Model\InternalApi\Person;

use Symfony\Component\Serializer\Annotation\SerializedName;

class PersonInfo
{
    /**
     * @SerializedName("name_info")
     */
    protected ?string $nomInfo;
    protected ?string $dataInfo;
    protected ?string $source;

    public function getNomInfo(): ?string
    {
        return $this->nomInfo;
    }

    public function setNomInfo(?string $nomInfo): PersonInfo
    {
        $this->nomInfo = $nomInfo;

        return $this;
    }

    public function getDataInfo(): ?string
    {
        return $this->dataInfo;
    }

    public function setDataInfo(?string $dataInfo): PersonInfo
    {
        $this->dataInfo = $dataInfo;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): PersonInfo
    {
        $this->source = $source;

        return $this;
    }
}
