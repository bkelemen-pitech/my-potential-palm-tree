<?php

declare(strict_types=1);

namespace App\Model\InternalApi;

class InternalResponse
{
    protected string $code;
    protected string $msg;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getMsg(): string
    {
        return $this->msg;
    }

    public function setMsg(string $msg): self
    {
        $this->msg = $msg;

        return $this;
    }
}
