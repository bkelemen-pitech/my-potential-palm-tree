<?php

declare(strict_types=1);

namespace App\Enum;

class VerificationStatusEnum
{
    public const NOT_VERIFIED = 0;
    public const OUT_OF_BOUNDS = 1;
    public const FRAUD = 2;
    public const UNREADABLE = 3;
    public const MISSING_VERSO = 4;
    public const NOT_CONSISTENT_NAME = 5;
    public const NOT_CONSISTENT_OTHER = 6;
    public const EXPIRED = 7;
    public const VERIFIED_NOT_ELIGIBLE = 8;
    public const VERIFIED_ELIGIBLE = 9;
}
