<?php

declare(strict_types=1);

namespace App\Enum;

class ResponseEnum
{
    // Error
    public const BODY = 'body';
    public const ERROR = 'error';
    public const STATUS = 'status';
    public const STATUS_CODE = 'statusCode';

    // Folders
    public const FOLDERS = 'folders';
    public const META = 'meta';
    public const TOTAL = 'total';
}
