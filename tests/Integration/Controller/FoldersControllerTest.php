<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Exception\InvalidDataException;
use App\Exception\ResourceNotFoundException;
use App\Facade\InternalApi\DocumentFacade;
use App\Fetcher\FolderFetcher;
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
}
