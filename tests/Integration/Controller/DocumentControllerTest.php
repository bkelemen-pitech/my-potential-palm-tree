<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Exception\ResourceNotFoundException;
use App\Facade\InternalApi\DocumentFacade;
use App\Tests\BaseApiTest;
use App\Tests\Enum\BaseEnum;
use App\Tests\Mocks\Data\DocumentsData;
use Prophecy\Prophecy\ObjectProphecy;

class DocumentControllerTest extends BaseApiTest
{
    public const PATH = 'api/v1/documents/';

    protected ObjectProphecy $documentFacade;

    public function setUp(): void
    {
        parent::setUp();
        $this->documentFacade = $this->prophesize(DocumentFacade::class);
        static::getContainer()->set(DocumentFacade::class, $this->documentFacade->reveal());
    }

    public function testGetDocumentByUid()
    {
        $documentUid = '617f896a61e39';
        $this->documentFacade->getDocuments($documentUid, false)->willReturn(DocumentsData::getInternalApiDocumentsResponse());
        $this->requestWithBody(BaseEnum::METHOD_GET, self::PATH . $documentUid);
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals(DocumentsData::getTestDocumentByUidExpectedData(), $this->getResponseContent());
    }

    public function testGetDocumentByUidWithContent()
    {
        $documentUid = '617f896a61e39';
        $this->documentFacade->getDocuments($documentUid, true)->willReturn(DocumentsData::getInternalApiDocumentsResponse(true));
        $this->requestWithBody(BaseEnum::METHOD_GET, self::PATH . $documentUid);
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals(DocumentsData::getTestDocumentByUidExpectedData(true), $this->getResponseContent());
    }

    public function testGetDocumentByUidNotFound()
    {
        $exception = new ResourceNotFoundException();
        $this->documentFacade->getDocuments('1', false)->willThrow($exception);
        $this->requestWithBody(BaseEnum::METHOD_GET, self::PATH . '1');

        $this->assertEquals(404, $this->getStatusCode());
    }
}
