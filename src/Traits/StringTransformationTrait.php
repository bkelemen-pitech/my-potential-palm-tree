<?php

declare(strict_types=1);

namespace App\Traits;

trait StringTransformationTrait
{
    public function snakeToCamel(string $str): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $str))));
    }

    protected function camelToSnake(string $str): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $str));
    }

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
