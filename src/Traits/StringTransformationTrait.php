<?php

declare(strict_types=1);

namespace App\Traits;

trait StringTransformationTrait
{
    protected function obfuscateData(array $data, array $obfuscateData): array
    {
        foreach ($obfuscateData as $key) {
            if (isset($data[$key]) && is_string($data[$key])) {
                $length = strlen($data[$key]);
                $data[$key] = substr($data[$key], 0, 2) . str_repeat("*", $length - 4) . substr($data[$key], -2);
            }
        }

        return $data;
    }
}
