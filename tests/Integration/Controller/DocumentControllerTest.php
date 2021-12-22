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
use Kyc\InternalApiBundle\Services\DocumentService as KycDocumentService;
use Prophecy\Prophecy\ObjectProphecy;

class DocumentControllerTest extends BaseApiTest
{
    public const PATH = 'api/v1/documents/';
    public const TREAT_DOCUMENT_PATH = 'api/v1/documents/treat';

    protected ObjectProphecy $documentFacade;
    protected ObjectProphecy $kycDocumentService;

    public function setUp(): void
    {
        parent::setUp();
        $this->documentFacade = $this->prophesize(DocumentFacade::class);
        static::getContainer()->set(DocumentFacade::class, $this->documentFacade->reveal());
        $this->kycDocumentService = $this->prophesize(KycDocumentService::class);
        static::getContainer()->set(KycDocumentService::class, $this->kycDocumentService->reveal());
    }

    public function testGetDocumentByUid()
    {
        $this->kycDocumentService
            ->getDocumentByUid(DocumentsData::DEFAULT_DOCUMENT_UID_TEST_DATA, false)
            ->shouldBeCalledOnce()
            ->willReturn(DocumentsData::getInternalApiDocumentsResponse());

        $this->requestWithBody(BaseEnum::METHOD_GET, self::PATH . DocumentsData::DEFAULT_DOCUMENT_UID_TEST_DATA);
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals(DocumentsData::getTestDocumentByUidExpectedData(), $this->getResponseContent());
    }

    public function testGetDocumentByUidWithContent()
    {
        $this->kycDocumentService
            ->getDocumentByUid(DocumentsData::DEFAULT_DOCUMENT_UID_TEST_DATA, true)
            ->shouldBeCalledOnce()
            ->willReturn(DocumentsData::getInternalApiDocumentsResponse(true));

        $this->requestWithBody(BaseEnum::METHOD_GET, self::PATH . DocumentsData::DEFAULT_DOCUMENT_UID_TEST_DATA . '?include_files=true');
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals(DocumentsData::getTestDocumentByUidExpectedData(true), $this->getResponseContent());
    }

    public function testGetDocumentByUidNotFound()
    {
        $this->kycDocumentService->getDocumentByUid('1', false)->willThrow(new ResourceNotFoundException());
        $this->requestWithBody(BaseEnum::METHOD_GET, self::PATH . '1');
        $this->assertEquals(404, $this->getStatusCode());
    }

    public function testTreatDocumentOk()
    {
        $this->markTestIncomplete('This test will be updated in KC-1485.');
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
        $this->markTestIncomplete('This test will be updated in KC-1485.');
        $this->requestWithBody(BaseEnum::METHOD_POST, self::TREAT_DOCUMENT_PATH, []);
        $this->assertEquals(400, $this->getStatusCode());
        $this->assertEquals([
            "documentUid" => "This value should not be blank.",
            "statusVerification2" => "This value should not be blank."
        ], $this->getResponseContent()['body']);
    }

    public function testTreatDocumentInvalidParametersType()
    {
        $this->markTestIncomplete('This test will be updated in KC-1485.');
        $body = [
          "document_uid" => 'test1234',
          "status_verification2" => "stringValue"
        ];

        $this->requestWithBody(BaseEnum::METHOD_POST, self::TREAT_DOCUMENT_PATH, $body);
        $this->assertEquals(400, $this->getStatusCode());
        $this->assertEquals(["statusVerification2" => "This value should be of type integer."], $this->getResponseContent()['body']);
    }

    public function testTreatDocumentThrowException()
    {
        $this->markTestIncomplete('This test will be updated in KC-1485.');
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
