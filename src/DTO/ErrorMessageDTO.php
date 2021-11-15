<?php

namespace App\DTO;

use App\Enum\ResponseEnum;

class ErrorMessageDTO
{
    protected int $statusCode;
    protected ?array $body;
    protected ?string $status;
    protected ?string $error;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): ErrorMessageDTO
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getBody(): ?array
    {
        return $this->body;
    }

    public function setBody(?array $body): ErrorMessageDTO
    {
        $this->body = $body;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): ErrorMessageDTO
    {
        $this->status = $status;

        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(string $error): ErrorMessageDTO
    {
        $this->error = $error;

        return $this;
    }

    public function toArray(): array
    {
        return [
            ResponseEnum::STATUS_CODE => $this->statusCode,
            ResponseEnum::BODY => $this->body,
            ResponseEnum::ERROR => $this->error,
            ResponseEnum::STATUS => $this->status,
        ];
    }
}
