<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\DTO\Folder\FolderByIdDTO;
use App\DTO\Person\PersonDTO;
use App\DTO\Person\PersonInfoDTO;
use App\Exception\InvalidDataException;
use App\Exception\ResourceNotFoundException;
use App\Facade\InternalApi\DocumentFacade;
use App\Fetcher\FolderFetcher;
use App\Tests\BaseApiTest;
use App\Tests\Enum\BaseEnum;
use App\Tests\Mocks\Data\DocumentsData;
use App\Tests\Mocks\Data\FolderData;
use Prophecy\Prophecy\ObjectProphecy;

class FolderControllerTest extends BaseApiTest
{
    public const GET_FOLDER = 'api/v1/folders/1';
    public const FOLDER_GET_DOCUMENTS = 'api/v1/folders/1/documents';

    protected ObjectProphecy $folderFetcher;
    protected ObjectProphecy $documentFacade;

    public function setUp(): void
    {
        parent::setUp();
        $this->folderFetcher = $this->prophesize(FolderFetcher::class);
        $this->documentFacade = $this->prophesize(DocumentFacade::class);
        static::getContainer()->set(FolderFetcher::class, $this->folderFetcher->reveal());
        static::getContainer()->set(DocumentFacade::class, $this->documentFacade->reveal());
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

    public function testGetFolderPersonsDocuments()
    {
        $this->documentFacade->getDocumentsByFolderId(1)->shouldBeCalledOnce()->willReturn(DocumentsData::getInternalApiDocumentsByFolderId());
        $this->requestWithBody(BaseEnum::METHOD_GET, self::FOLDER_GET_DOCUMENTS);
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals(DocumentsData::getTestFolderPersonsDocumentExpectedData(), $this->getResponseContent());
    }

    public function testGetDocumentsException()
    {
        $exception = new InvalidDataException('Failed.');
        $this->documentFacade->getDocumentsByFolderId(1)->shouldBeCalledOnce()->willThrow($exception);
        $this->requestWithBody(BaseEnum::METHOD_GET, self::FOLDER_GET_DOCUMENTS);
        $this->assertEquals(400, $this->getStatusCode());
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
                ->setPersonId(30)
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
                ->setPersonUid('1')
                ->setPersonId(31),
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
        ];
    }
}
