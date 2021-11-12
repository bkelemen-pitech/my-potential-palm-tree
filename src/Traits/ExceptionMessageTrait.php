<?php

declare(strict_types=1);

namespace App\Traits;

use App\DTO\ErrorMessageDTO;
use App\Enum\ResponseEnum;

trait ExceptionMessageTrait
{
    public function buildExceptionMessage(int $statusCode, string $errorMessage): ErrorMessageDTO
    {
        $message = new ErrorMessageDTO();

        $message
            ->setStatusCode($statusCode)
            ->setBody(json_decode($errorMessage, true))
            ->setError($errorMessage)
            ->setStatus(ResponseEnum::ERROR);

        return $message;
    }
}
