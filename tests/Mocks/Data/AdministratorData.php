<?php

declare(strict_types=1);

namespace App\Tests\Mocks\Data;

use Kyc\InternalApiBundle\Model\InternalApi\Administrator\Administrator;
use Kyc\InternalApiBundle\Model\Response\Administrator\AdministratorModelResponse;

class AdministratorData
{
    public const ADMINISTRATOR_DATA = [
        'id' => 12,
        'login' => 'alice',
        'role' => 1,
        'email' => 'marketing@beprems.com',
        'groupId' => 1,
    ];

    public static function createAdministratorModelResponse(array $data = self::ADMINISTRATOR_DATA): array
    {
        return [
            (new AdministratorModelResponse())
                ->setId($data['id'])
                ->setLogin($data['login'])
                ->setRole($data['role'])
                ->setEmail($data['email'])
                ->setGroupId($data['groupId']),
        ];
    }

    public static function getAdministrators(): array
    {
        return [
            "administrators" => [
                [
                    "id" => 12,
                    "login" => "alice",
                    "role" => 1,
                    "email" => "marketing@beprems.com",
                    "group_id" => 1,
                ],
            ],
        ];
    }

    public static function getAdministratorsObject(): array
    {
        return [
            (new AdministratorModelResponse())
                ->setId(12)
                ->setLogin("alice")
                ->setRole(1)
                ->setEmail("marketing@beprems.com")
                ->setGroupId(1),
        ];
    }
}
