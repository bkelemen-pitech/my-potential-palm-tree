<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Exception\InvalidDataException;
use App\Exception\ResourceNotFoundException;
use App\Facade\InternalApi\DocumentFacade;
use App\Model\Request\Document\TreatDocumentModel;
use App\Tests\BaseApiTest;
use App\Tests\Enum\BaseEnum;
use App\Tests\Mocks\Data\DocumentsData;
use Prophecy\Prophecy\ObjectProphecy;

class DocumentControllerTest extends BaseApiTest
{
    public const PATH = 'api/v1/documents/';
    public const TREAT_DOCUMENT_PATH = 'api/v1/documents/treat';

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
        $this->requestWithBody(BaseEnum::METHOD_GET, self::PATH . $documentUid . '?include_files=true');
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals(DocumentsData::getTestDocumentByUidExpectedData(true), $this->getResponseContent());
    }

    public function testGetDocumentByUidNotFound()
    {
        $this->documentFacade->getDocuments('1', false)->willThrow(new ResourceNotFoundException());
        $this->requestWithBody(BaseEnum::METHOD_GET, self::PATH . '1');
        $this->assertEquals(404, $this->getStatusCode());
    }

    public function testTreatDocumentOk()
    {
        $body = [
          "document_uid" => 'test1234',
          "status_verification2" => 3
        ];

        $this->requestWithBody(BaseEnum::METHOD_POST, self::TREAT_DOCUMENT_PATH, $body);
        $this->assertEquals(204, $this->getStatusCode());
        $this->assertEquals(null, $this->getResponseContent());
    }

    public function testTreatDocumentEmptyBody()
    {
        $this->requestWithBody(BaseEnum::METHOD_POST, self::TREAT_DOCUMENT_PATH, []);
        $this->assertEquals(400, $this->getStatusCode());
        $this->assertEquals('{"documentUid":"This value should not be blank.","statusVerification2":"This value should not be blank."}', $this->getResponseContent()['detail']);
    }

    public function testTreatDocumentInvalidParametersType()
    {
        $body = [
          "document_uid" => 'test1234',
          "status_verification2" => "stringValue"
        ];

        $this->requestWithBody(BaseEnum::METHOD_POST, self::TREAT_DOCUMENT_PATH, $body);
        $this->assertEquals(400, $this->getStatusCode());
        $this->assertEquals('{"statusVerification2":"This value should be of type integer."}', $this->getResponseContent()['detail']);
    }

    public function testTreatDocumentThrowException()
    {
        $body = [
            "document_uid" => 'test1234',
            "status_verification2" => 100
        ];

        $treatDocumentModel = new TreatDocumentModel();
        $treatDocumentModel
            ->setDocumentUid($body['document_uid'])
            ->setStatusVerification2($body['status_verification2']);

        $this->documentFacade->treatDocument($treatDocumentModel)->willThrow(new InvalidDataException());
        $this->requestWithBody(BaseEnum::METHOD_POST, self::TREAT_DOCUMENT_PATH, $body);
        $this->assertEquals(400, $this->getStatusCode());
    }
}
