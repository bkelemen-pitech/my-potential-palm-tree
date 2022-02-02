<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Exception\InvalidDataException;
use App\Tests\BaseApiTest;
use App\Tests\Enum\BaseEnum;
use App\Tests\Mocks\Data\DocumentsData;
use Kyc\InternalApiBundle\Exception\InvalidDataException as InternalApiInvalidDataException;
use Kyc\InternalApiBundle\Exception\ResourceNotFoundException;
use Kyc\InternalApiBundle\Model\Request\Document\DeleteDocumentModel;
use Kyc\InternalApiBundle\Model\Request\Document\TreatDocumentModel;
use Kyc\InternalApiBundle\Service\DocumentService as InternalApiDocumentService;
use Prophecy\Prophecy\ObjectProphecy;

class DocumentControllerTest extends BaseApiTest
{
    public const PATH = 'api/v1/documents/';
    public const TREAT_DOCUMENT_PATH = 'api/v1/documents/treat';
    public const DOCUMENT_FIELDS_PATH = 'api/v1/documents/fields';
    public const DOCUMENT_DATA_LOGS_PATH = 'api/v1/documents/document-data-logs';
    public const DELETE_DOCUMENT_PATH = 'api/v1/documents/617f896a61e39';

    protected ObjectProphecy $internalApiDocumentService;

    public function setUp(): void
    {
        parent::setUp();
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

    public function testGetDocumentFieldsSuccess()
    {
        $documentFieldsModelRequest = DocumentsData::createDocumentFieldsRequestModel();
        $documentFieldsModelResponse = DocumentsData::createDocumentFieldsModelResponse();

        $this->internalApiDocumentService
            ->getDocumentFields($documentFieldsModelRequest)
            ->shouldBeCalledOnce()
            ->willReturn($documentFieldsModelResponse);

        $this->requestWithBody(
            BaseEnum::METHOD_GET,
            self::DOCUMENT_FIELDS_PATH,
            [],
            [],
            true,
            ['agency_id' => 1, 'document_type_id' => 1, 'person_type_id' => 1]
        );
        $this->assertEquals(200, $this->getStatusCode());

        $this->assertEquals(
            [
                [
                    'dbFieldName' => 'nom',
                    'label' => 'Nom',
                    'order' => 1,
                    'format' => 1,
                    'mandatory' => 1,
                    'helperMethod' => 'test',
                    'ocrField' => 1,
                    'validatorMethod' => 'validator',
                ]
            ],
            $this->getResponseContent()
        );
    }

    public function testGetDocumentFieldsException()
    {
        $documentFieldsModelRequest = DocumentsData::createDocumentFieldsRequestModel();

        $this->internalApiDocumentService
            ->getDocumentFields($documentFieldsModelRequest)
            ->shouldBeCalledOnce()
            ->willThrow(new InternalApiInvalidDataException('Invalid request'));

        $this->requestWithBody(
            BaseEnum::METHOD_GET,
            self::DOCUMENT_FIELDS_PATH,
            [],
            [],
            true,
            ['agency_id' => 1, 'document_type_id' => 1, 'person_type_id' => 1]
        );
        $this->assertEquals(400, $this->getStatusCode());
    }

    public function testGetDocumentDataLogsSuccess()
    {
        $documentDataLogsModelRequest = DocumentsData::createDocumentDataLogsRequestModel();
        $documentDataLogsModelResponse = DocumentsData::createDocumentDataLogsModelResponse();

        $this->internalApiDocumentService
            ->getDocumentDataLogs($documentDataLogsModelRequest)
            ->shouldBeCalledOnce()
            ->willReturn($documentDataLogsModelResponse);

        $this->requestWithBody(
            BaseEnum::METHOD_GET,
            self::DOCUMENT_DATA_LOGS_PATH,
            [],
            [],
            true,
            ['administrator-id' => 1, 'document-ids' => [1, 2]]
        );
        $this->assertEquals(200, $this->getStatusCode());

        $this->assertEquals(
            ['documentDataLogs' => [
                [
                    'documentId' => 1,
                    'verification2Status' => 2,
                    'administratorId' => 1,
                    'createdAt' => '2020-02-02T00:00:00+00:00',
                ]
            ]],
            $this->getResponseContent()
        );
    }

    public function testGetDocumentDataLogsException()
    {
        $documentDataLogsModelRequest = DocumentsData::createDocumentDataLogsRequestModel();

        $this->internalApiDocumentService
            ->getDocumentDataLogs($documentDataLogsModelRequest)
            ->shouldBeCalledOnce()
            ->willThrow(new InternalApiInvalidDataException('Invalid request'));

        $this->requestWithBody(
            BaseEnum::METHOD_GET,
            self::DOCUMENT_DATA_LOGS_PATH,
            [],
            [],
            true,
            ['administrator-id' => 1, 'document-ids' => [1, 2]]
        );
        $this->assertEquals(400, $this->getStatusCode());
    }


    public function testDeleteDocumentOk()
    {
        $this->internalApiDocumentService
            ->deleteDocumentByUid(DocumentsData::createDeleteDocumentModel())
            ->shouldBeCalledOnce();

        $this->requestWithBody(BaseEnum::METHOD_DELETE, self::DELETE_DOCUMENT_PATH);
        $this->assertEquals(204, $this->getStatusCode());
        $this->assertEquals(null, $this->getResponseContent());
    }

    public function testDeleteDocumentThrowException()
    {
        $deleteDocumentModelData = [
          'documentUid' => DocumentsData::DEFAULT_DOCUMENT_UID_TEST_DATA,
          'administratorId' => '1'
        ];

        $this->internalApiDocumentService
            ->deleteDocumentByUid(DocumentsData::createDeleteDocumentModel($deleteDocumentModelData))
            ->willThrow(new InternalApiInvalidDataException());
        $this->requestWithBody(BaseEnum::METHOD_DELETE, self::DELETE_DOCUMENT_PATH);
        $this->assertEquals(400, $this->getStatusCode());
    }

    public function testDeleteDocumentThrowNotFoundException()
    {
        $deleteDocumentModel = new DeleteDocumentModel();
        $deleteDocumentModel
            ->setDocumentUid(DocumentsData::DEFAULT_DOCUMENT_UID_TEST_DATA)
            ->setAdministratorId('1');

        $this->internalApiDocumentService->deleteDocumentByUid($deleteDocumentModel)->willThrow(new ResourceNotFoundException());
        $this->requestWithBody(BaseEnum::METHOD_DELETE, self::DELETE_DOCUMENT_PATH);
        $this->assertEquals(404, $this->getStatusCode());
    }
}
