<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\DTO\Folder\FolderByIdDTO;
use App\DTO\Person\PersonDTO;
use App\DTO\Person\PersonInfoDTO;
use App\Exception\ResourceNotFoundException;
use App\Fetcher\FolderFetcher;
use App\Tests\BaseApiTest;
use App\Tests\Enum\BaseEnum;
use App\Tests\Mocks\Data\FolderData;
use Prophecy\Prophecy\ObjectProphecy;

class FolderControllerTest extends BaseApiTest
{
    public const GET_FOLDER = 'api/v1/folders/1';

    protected ObjectProphecy $folderFetcher;

    public function setUp(): void
    {
        parent::setUp();
        $this->folderFetcher = $this->prophesize(FolderFetcher::class);
        static::getContainer()->set(FolderFetcher::class, $this->folderFetcher->reveal());
    }

    public function testGetFolderById()
    {
        $persons = $this->getPersonDtoTestData();
        $folderData = $this->getFolderByIdDtoTestData($persons);
        $expected = $this->getFolderByIdExpectedData();

        $this->folderFetcher->getFolderData(1, FolderData::GET_FOLDER_BY_ID_ORDER_FILTERS)->willReturn($folderData);
        $this->requestWithBody(BaseEnum::METHOD_GET, self::GET_FOLDER);
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals($expected, $this->getResponseContent());
    }

    public function testGetFolderByIdNotFound()
    {
        $this->folderFetcher->getFolderData(1, FolderData::GET_FOLDER_BY_ID_ORDER_FILTERS)->willThrow(new ResourceNotFoundException());
        $this->requestWithBody(BaseEnum::METHOD_GET, self::GET_FOLDER);

        $this->assertEquals(404, $this->getStatusCode());
    }

    private function getPersonDtoTestData()
    {
        return [
            (new PersonDTO())
                ->setLastName('Smith')
                ->setFirstName('John')
                ->setDateOfBirth('12-01-2020')
                ->setPersonTypeId(1)
                ->setPersonUid('1')
                ->setPersonInfo(
                    [
                        (new PersonInfoDTO())
                            ->setNameInfo('dossier')
                            ->setDataInfo('39')
                            ->setSource(null)
                    ]
                ),
            (new PersonDTO())
                ->setLastName('Smithy')
                ->setFirstName('Johny')
                ->setDateOfBirth('12-03-2020')
                ->setPersonTypeId(1)
                ->setPersonUid('1'),
        ];
    }

    private function getFolderByIdDtoTestData($persons)
    {
        return (new FolderByIdDTO())
        ->setId(1)
        ->setPartnerFolderId('2')
        ->setStatus(3)
        ->setWorkflowStatus(1400)
        ->setLabel(2)
        ->setSubscription(20)
        ->setPersons($persons);
    }

    private function getFolderByIdExpectedData()
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
                    'personInfo' => [],
                ],
            ],
        ];
    }
}
