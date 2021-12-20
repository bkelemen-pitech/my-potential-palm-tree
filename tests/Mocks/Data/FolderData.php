<?php

declare(strict_types=1);

namespace App\Tests\Mocks\Data;

use Kyc\InternalApiBundle\Model\InternalApi\Folder\FolderById;
use Kyc\InternalApiBundle\Model\InternalApi\Folder\GetFolderByIdResponse;
use Kyc\InternalApiBundle\Model\Response\Folder\FolderByIdModelResponse;

class FolderData
{
    public const GET_FOLDER_BY_ID_ORDER_FILTERS = [
        'person-order' => 'ASC NULLS FIRST',
        'person-info-order' => 'ASC',
        'person-order-by' => 'prenom,nom',
        'person-info-order-by' => 'source,creation'
    ];

    public const FOLDER_BY_ID_DATA = [1, '2', 3, 1400, 2, 20, 7];

    public static function createFolderByIdModelResponse(array $data = self::FOLDER_BY_ID_DATA): FolderByIdModelResponse
    {
        return (new FolderByIdModelResponse())
            ->setId($data[0])
            ->setPartnerFolderId($data[1])
            ->setStatus($data[2])
            ->setWorkflowStatus($data[3])
            ->setLabel($data[4])
            ->setSubscription($data[5])
            ->setAgencyId($data[6]);
    }


    public static function createInternalApiFolderByIdResponse(FolderById $resource): GetFolderByIdResponse
    {
        return (new GetFolderByIdResponse())
            ->setCode('OK')
            ->setMsg('Success')
            ->setResource($resource);
    }

    public static function getFolderByIdExpectedData(): array
    {
        return [
            'id' => 1,
            'partnerFolderId' => '2',
            'status' => 3,
            'workflowStatus' => 1400,
            'label' => 2,
            'subscription' => 20,
            'persons' => [
                [
                    'lastName' => 'Smith',
                    'firstName' => 'John',
                    'dateOfBirth' => '2020-01-12T00:00:00+00:00',
                    'personTypeId' => 1,
                    'personUid' => '1',
                    'personId' => 30,
                    'personInfo' => [
                        [
                            'nameInfo' => 'dossier',
                            'dataInfo' => '39',
                            'source' => null,
                        ],
                    ],
                ],
                [
                    'lastName' => 'Smithy',
                    'firstName' => 'Johny',
                    'dateOfBirth' => '2020-03-12T00:00:00+00:00',
                    'personTypeId' => 1,
                    'personUid' => '1',
                    'personId' => 31,
                    'personInfo' => [],
                ],
            ],
            'agencyId' => 7
        ];
    }
}
