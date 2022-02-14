<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    public const INVALID_DATA_EXCEPTION_MESSAGE = 'Invalid data exception!';
}
