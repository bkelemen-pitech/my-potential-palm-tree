<?php

declare(strict_types=1);

namespace App\Tests\Mocks\Data;

use App\Tests\Enum\AdministratorEnum;
use Kyc\InternalApiBundle\Model\InternalApi\Folder\FolderById;
use Kyc\InternalApiBundle\Model\InternalApi\Folder\GetFolderByIdResponse;
use Kyc\InternalApiBundle\Model\Response\Folder\AssignedAdministratorModelResponse;
use Kyc\InternalApiBundle\Model\Response\Folder\FolderByIdModelResponse;
use Kyc\InternalApiBundle\Model\Response\Folder\FolderModelResponse;

class FolderData
{
    public const GET_FOLDER_BY_ID_ORDER_FILTERS = [
        'person-order' => 'ASC NULLS FIRST',
        'person-info-order' => 'ASC',
        'person-order-by' => 'prenom,nom',
        'person-info-order-by' => 'source,creation',
    ];

    public const FOLDER_BY_ID_DATA = [
        'folderId' => 1,
        'partnerFolderId' => '2',
        'status' => 3,
        'workflowStatus' => 10400,
        'label' => 2,
        'subscription' => 20,
        'agencyId' => 7,
        'login' => 'Test login',
    ];

    public const GET_FOLDERS_WITH_VIEW_2 = [
        [
            [
                'view' => 2,
                'filters' => [
                    'user_id' => [
                        1,
                    ],
                ],
            ],
            2,
        ],
        [
            [
                'view' => 2,
                'view_criteria' => 1,
                'filters' => [
                    'user_id' => [
                        1,
                    ],
                ],
            ],
            1,
        ],
        [
            [
                'view' => 2,
                'view_criteria' => 2,
            ],
            2,
        ],
    ];

    public const GET_FOLDERS_COUNT = [
        'filters' => [
            'view_1' => [
                'workflow_status' => [10300],
            ],
            'view_2' => [
                'workflow_status' => [10301, 10302, 10303, 10304],
            ],
        ],
    ];

    public const GET_FOLDERS_COUNT_RESPONSE = [
        'folders' => [
            [
                'total' => 12,
                'view' => 1,
            ],
            [
                'total' => 6,
                'view' => 2,
            ],
        ],
    ];

    public static function createFolderByIdModelResponse(array $data = self::FOLDER_BY_ID_DATA): FolderByIdModelResponse
    {
        return (new FolderByIdModelResponse())
            ->setId($data['folderId'])
            ->setPartnerFolderId($data['partnerFolderId'] ?? null)
            ->setStatus($data['status'] ?? null)
            ->setWorkflowStatus($data['workflowStatus'] ?? null)
            ->setLabel($data['label'] ?? null)
            ->setSubscription($data['subscription'] ?? null)
            ->setAgencyId($data['agencyId'] ?? null)
            ->setLogin($data['login'] ?? null);
    }

    public static function getFolderByIdExpectedData(): array
    {
        return [
            'id' => 1,
            'partner_folder_id' => '2',
            'status' => 3,
            'workflow_status' => 10400,
            'label' => 2,
            'subscription' => 20,
            'persons' => [
                [
                    'last_name' => 'Smith',
                    'first_name' => 'John',
                    'date_of_birth' => '2020-01-12T00:00:00+00:00',
                    'person_type_id' => 1,
                    'person_uid' => '1',
                    'person_id' => 30,
                    'person_info' => [
                        [
                            'name_info' => 'dossier',
                            'data_info' => '39',
                            'source' => null,
                        ],
                    ],
                ],
                [
                    'last_name' => 'Smithy',
                    'first_name' => 'Johny',
                    'date_of_birth' => '2020-03-12T00:00:00+00:00',
                    'person_type_id' => 1,
                    'person_uid' => '1',
                    'person_id' => 31,
                    'person_info' => [],
                ],
            ],
            'agency_id' => 7,
            'login' => 'Test login',
        ];
    }

    public static function getFolderModelResponse1(): FolderModelResponse
    {
        return (new FolderModelResponse())
            ->setFolderId(1)
            ->setFolder('1a')
            ->setFirstName('First Name 1')
            ->setLastName('Last Name 1')
            ->setDateOfBirth('2020-02-02T00:00:00+00:00')
            ->setSubscription(10);
    }

    public static function getFolderModelResponse1Assigned(): FolderModelResponse
    {
        return (new FolderModelResponse())
            ->setFolderId(1)
            ->setFolder('1a')
            ->setFirstName('First Name 1')
            ->setLastName('Last Name 1')
            ->setDateOfBirth('2020-02-02T00:00:00+00:00')
            ->setSubscription(10)
            ->setAssignedTo('Admin1');
    }

    public static function getFolderModelResponse2(): FolderModelResponse
    {
        return (new FolderModelResponse())
            ->setFolderId(2)
            ->setFolder('2a')
            ->setFirstName('First Name 2')
            ->setLastName('Last Name 2')
            ->setDateOfBirth('2020-02-02T00:00:00+00:00')
            ->setSubscription(20);
    }

    public static function getFolderModelResponse2Assigned(): FolderModelResponse
    {
        return (new FolderModelResponse())
            ->setFolderId(2)
            ->setFolder('2a')
            ->setFirstName('First Name 2')
            ->setLastName('Last Name 2')
            ->setDateOfBirth('2020-02-02T00:00:00+00:00')
            ->setSubscription(20)
            ->setAssignedTo('Admin2');
    }

    public static function getAssignedAdministratorModelResponse1(): AssignedAdministratorModelResponse
    {
        return (new AssignedAdministratorModelResponse())
            ->setAdministratorId(1)
            ->setStatus(AdministratorEnum::STATUS_IN_PROGRESS)
            ->setUsername('Admin1')
            ->setFolderId(1);
    }

    public static function getAssignedAdministratorModelResponse2(): AssignedAdministratorModelResponse
    {
        return (new AssignedAdministratorModelResponse())
            ->setAdministratorId(2)
            ->setStatus(AdministratorEnum::STATUS_IN_PROGRESS)
            ->setUsername('Admin2')
            ->setFolderId(2);
    }
}
