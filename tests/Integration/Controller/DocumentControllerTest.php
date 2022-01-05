<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Exception\InvalidDataException;
use App\Exception\ResourceNotFoundException;
use App\Facade\InternalApi\DocumentFacade;
use App\Tests\BaseApiTest;
use App\Tests\Enum\BaseEnum;
use App\Tests\Mocks\Data\DocumentsData;
use Kyc\InternalApiBundle\Model\Request\Document\TreatDocumentModel;
use Kyc\InternalApiBundle\Service\DocumentService as InternalApiDocumentService;
use Prophecy\Prophecy\ObjectProphecy;

class DocumentControllerTest extends BaseApiTest
{
    public const PATH = 'api/v1/documents/';
    public const TREAT_DOCUMENT_PATH = 'api/v1/documents/treat';

    protected ObjectProphecy $documentFacade;
    protected ObjectProphecy $internalApiDocumentService;

    public function setUp(): void
    {
        parent::setUp();
        $this->documentFacade = $this->prophesize(DocumentFacade::class);
        static::getContainer()->set(DocumentFacade::class, $this->documentFacade->reveal());
        $this->internalApiDocumentService = $this->prophesize(InternalApiDocumentService::class);
        static::getContainer()->set(InternalApiDocumentService::class, $this->internalApiDocumentService->reveal());
    }

    public function testGetDocumentByUid()
    {
        $this->internalApiDocumentService
            ->getDocumentByUid(DocumentsData::DEFAULT_DOCUMENT_UID_TEST_DATA, false)
            ->shouldBeCalledOnce()
            ->willReturn(DocumentsData::getInternalApiDocumentsResponse());

        $this->requestWithBody(BaseEnum::METHOD_GET, self::PATH . DocumentsData::DEFAULT_DOCUMENT_UID_TEST_DATA);
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals(DocumentsData::getTestDocumentByUidExpectedData(), $this->getResponseContent());
    }

    public function testGetDocumentByUidWithContent()
    {
        $this->internalApiDocumentService
            ->getDocumentByUid(DocumentsData::DEFAULT_DOCUMENT_UID_TEST_DATA, true)
            ->shouldBeCalledOnce()
            ->willReturn(DocumentsData::getInternalApiDocumentsResponse(true));

        $this->requestWithBody(BaseEnum::METHOD_GET, self::PATH . DocumentsData::DEFAULT_DOCUMENT_UID_TEST_DATA . '?include_files=true');
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals(DocumentsData::getTestDocumentByUidExpectedData(true), $this->getResponseContent());
    }

    public function testGetDocumentByUidNotFound()
    {
        $this->internalApiDocumentService->getDocumentByUid('1', false)->willThrow(new ResourceNotFoundException());
        $this->requestWithBody(BaseEnum::METHOD_GET, self::PATH . '1');
        $this->assertEquals(404, $this->getStatusCode());
    }

    public function testTreatDocumentOk()
    {
        $body = [
          "document_uid" => DocumentsData::DEFAULT_DOCUMENT_UID_TEST_DATA,
          "status_verification2" => 8
        ];

        $this->internalApiDocumentService
            ->treatDocument(DocumentsData::createTreatDocumentModel())
            ->shouldBeCalledOnce();

        $this->requestWithBody(BaseEnum::METHOD_POST, self::TREAT_DOCUMENT_PATH, $body);
        $this->assertEquals(204, $this->getStatusCode());
        $this->assertEquals(null, $this->getResponseContent());
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

        $this->internalApiDocumentService->treatDocument($treatDocumentModel)->willThrow(new InvalidDataException());
        $this->requestWithBody(BaseEnum::METHOD_POST, self::TREAT_DOCUMENT_PATH, $body);
        $this->assertEquals(400, $this->getStatusCode());
    }
}
