<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Enum\PersonEnum;
use App\Exception\InvalidDataException;
use App\Exception\ResourceNotFoundException;
use App\Facade\InternalApi\DocumentFacade;
use App\Facade\InternalApi\PersonFacade;
use App\Fetcher\FolderFetcher;
use App\Services\PersonService;
use App\Tests\BaseApiTest;
use App\Tests\Enum\BaseEnum;
use App\Tests\Mocks\Data\DocumentsData;
use App\Tests\Mocks\Data\FolderData;
use App\Tests\Mocks\Data\PersonData;
use Prophecy\Prophecy\ObjectProphecy;

class FolderControllerTest extends BaseApiTest
{
    public const GET_FOLDER = 'api/v1/folders/1';
    public const FOLDER_GET_DOCUMENTS = 'api/v1/folders/1/documents';
    public const FOLDER_ADD_PERSON = 'api/v1/folders/1/add-person';

    protected ObjectProphecy $folderFetcher;
    protected ObjectProphecy $documentFacade;
    protected ObjectProphecy $personService;

    public function setUp(): void
    {
        parent::setUp();
        $this->folderFetcher = $this->prophesize(FolderFetcher::class);
        $this->documentFacade = $this->prophesize(DocumentFacade::class);
        $this->personService = $this->prophesize(PersonFacade::class);
        static::getContainer()->set(FolderFetcher::class, $this->folderFetcher->reveal());
        static::getContainer()->set(DocumentFacade::class, $this->documentFacade->reveal());
        static::getContainer()->set(PersonFacade::class, $this->personService->reveal());
    }

    public function testGetFolderById()
    {
        $persons = PersonData::getFolderPersonsDTOByIdTestData();
        $this->folderFetcher->getFolderData(1, FolderData::GET_FOLDER_BY_ID_ORDER_FILTERS)->willReturn(FolderData::createFolderByIdDTO($persons));
        $this->requestWithBody(BaseEnum::METHOD_GET, self::GET_FOLDER);

        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals(FolderData::getFolderByIdExpectedData(), $this->getResponseContent());
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

    public function testAddPersonOk()
    {
        $body = [
            'personTypeId' => 1,
            'agencyId' => 709
        ];

        $this->personService
            ->addPerson(PersonData::createAddPersonModel())
            ->shouldBeCalledOnce()
            ->willReturn([PersonEnum::BEPREMS_PERSON_UID => PersonData::DEFAULT_PERSON_UID_TEST_DATA]);
        $this->requestWithBody(BaseEnum::METHOD_POST, self::FOLDER_ADD_PERSON, $body);

        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals([PersonEnum::PERSON_UID => PersonData::DEFAULT_PERSON_UID_TEST_DATA], $this->getResponseContent());
    }

    public function testAddPersonEmptyBodyTriggerValidations()
    {
        $this->requestWithBody(BaseEnum::METHOD_POST, self::FOLDER_ADD_PERSON, []);

        $this->assertEquals(400, $this->getStatusCode());
        $this->assertEquals([
            'agencyId' => 'This value should not be blank.',
            'personTypeId' => 'This value should not be blank.'
        ], $this->getResponseContent()['body']);
    }

    public function testAddPersonWrongBodyParamsTypeTriggerValidations()
    {
        $body = [
            'personTypeId' => "1",
            'agencyId' => "709"
        ];

        $this->requestWithBody(BaseEnum::METHOD_POST, self::FOLDER_ADD_PERSON, $body);

        $this->assertEquals(400, $this->getStatusCode());
        $this->assertEquals([
            'agencyId' => 'This value should be of type integer.',
            'personTypeId' => 'This value should be of type integer.'
        ], $this->getResponseContent()['body']);
    }
}
