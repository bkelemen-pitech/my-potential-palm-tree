<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Tests\BaseApiTest;
use App\Tests\Enum\BaseEnum;
use App\Tests\Mocks\Data\DocumentsData;
use Kyc\InternalApiBundle\Exception\InvalidDataException as InternalApiInvalidDataException;
use Kyc\InternalApiBundle\Exception\ResourceNotFoundException;
use Kyc\InternalApiBundle\Service\DocumentService as InternalApiDocumentService;
use Prophecy\Prophecy\ObjectProphecy;

class DocumentControllerTest extends BaseApiTest
{
    public const PATH = 'api/v1/documents/';
    public const TREAT_DOCUMENT_PATH = 'api/v1/documents/617f896a61e39/treat';
    public const DOCUMENT_FIELDS_PATH = 'api/v1/documents/fields';
    public const DOCUMENT_DATA_LOGS_PATH = 'api/v1/documents/document-data-logs';
    public const DELETE_DOCUMENT_PATH = 'api/v1/documents/617f896a61e39';
    public const DOCUMENT_TYPES_PATH = 'api/v1/documents/types';
    public const UPDATE_DOCUMENT_TYPE_PATH = 'api/v1/documents/testUid';

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
          "verification2_status" => 8,
          "agency_id" => 1,
          "folder_id" => 1
        ];

        $this->internalApiDocumentService
            ->treatDocument(DocumentsData::createTreatDocumentModel())
            ->shouldBeCalledOnce();

        $this->requestWithBody(
            BaseEnum::METHOD_POST,
            self::TREAT_DOCUMENT_PATH,
            $body,
            [],
            true,
            ['documentUid' => DocumentsData::DEFAULT_DOCUMENT_UID_TEST_DATA]
        );
        $this->assertEquals(204, $this->getStatusCode());
        $this->assertEquals(null, $this->getResponseContent());
    }

    public function testTreatDocumentThrowException()
    {
        $body = [
            "verification2_status" => '100',
            "agency_id" => 1,
            "folder_id" => 1
        ];

        $treatDocumentModel = DocumentsData::createTreatDocumentModel();
        $treatDocumentModel->setVerification2Status($body['verification2_status']);

        $this->internalApiDocumentService
            ->treatDocument($treatDocumentModel)
            ->willThrow(new InternalApiInvalidDataException());

        $this->requestWithBody(
            BaseEnum::METHOD_POST,
            self::TREAT_DOCUMENT_PATH,
            $body,
            [],
            true,
            ['documentUid' => DocumentsData::DEFAULT_DOCUMENT_UID_TEST_DATA]
        );

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
                    'db_field_name' => 'nom',
                    'label' => 'Nom',
                    'order' => 1,
                    'format' => 1,
                    'mandatory' => 1,
                    'helper_method' => 'test',
                    'ocr_field' => 1,
                    'validator_method' => 'validator',
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

    /**
     * @dataProvider getDocumentDataLogsDataProvider
     */
    public function testGetDocumentDataLogsSuccess(?string $internalApiDataValue, ?array $responseValue)
    {
        $documentDataLogsModelRequest = DocumentsData::createDocumentDataLogsRequestModel();
        $documentDataLogsModelResponse = DocumentsData::createDocumentDataLogsModelResponse($internalApiDataValue);

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
            ['document_data_logs' => [
                [
                    'document_id' => 1,
                    'verification2_status' => 2,
                    'administrator_id' => 1,
                    'created_at' => '2020-02-02T00:00:00+00:00',
                    'data' => $responseValue,
                    'document_data_log_id' => 1,
                ]
            ]],
            $this->getResponseContent()
        );
    }

    public function getDocumentDataLogsDataProvider(): array
    {
        return [
            [
                'a:3:{s:3:"nom";s:3:"Nom";i:0;s:6:"prenom";s:7:"type_id";s:2:"ID";}',
                [
                    'nom' => 'Nom',
                    'prenom' => null,
                    'type_id' => "ID"
                ]
            ],
            [null, null],
            ['test', null],
            ["[]", null],
            ["a:0:{}", null]
        ];
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
        $deleteDocumentModelData = [
            'documentUid' => DocumentsData::DEFAULT_DOCUMENT_UID_TEST_DATA,
            'administratorId' => '1'
        ];

        $this->internalApiDocumentService
            ->deleteDocumentByUid(DocumentsData::createDeleteDocumentModel($deleteDocumentModelData))
            ->willThrow(new ResourceNotFoundException());
        $this->requestWithBody(BaseEnum::METHOD_DELETE, self::DELETE_DOCUMENT_PATH);
        $this->assertEquals(404, $this->getStatusCode());
    }

    public function testGetDocumentTypesSuccess()
    {
        $this->internalApiDocumentService
            ->getDocumentTypes(DocumentsData::getDocumentTypesModelRequest())
            ->shouldBeCalledOnce()
            ->willReturn(DocumentsData::getDocumentTypesModelResponse());

        $this->requestWithBody(
            BaseEnum::METHOD_GET,
            self::DOCUMENT_TYPES_PATH,
            [],
            [],
            true,
            ['agency_id' => 1, 'person_type_id' => 1]
        );
        $this->assertEquals(200, $this->getStatusCode());

        $this->assertEquals(
            [
                [
                    'document_type_id' => 1,
                    'sub_document_type_id' => 61,
                    'treatment_instruction' => 'test1',
                    'sub_treatment_instruction' => 'test2',
                ],
                [
                    'document_type_id' => 4,
                    'sub_document_type_id' => 5,
                    'treatment_instruction' => 'test3',
                    'sub_treatment_instruction' => 'test4',
                ]
            ],
            $this->getResponseContent()
        );
    }

    public function testGetDocumentTypesException()
    {
        $this->internalApiDocumentService
            ->getDocumentTypes(DocumentsData::getDocumentTypesModelRequest())
            ->shouldBeCalledOnce()
            ->willThrow(new InternalApiInvalidDataException('Invalid request'));

        $this->requestWithBody(
            BaseEnum::METHOD_GET,
            self::DOCUMENT_TYPES_PATH,
            [],
            [],
            true,
            ['agency_id' => 1, 'person_type_id' => 1]
        );
        $this->assertEquals(400, $this->getStatusCode());
    }

    public function testUpdateDocumentTypeSuccess()
    {
        $body = [
            'document_type_id' => 1,
            'sub_document_type_id' => 2,
        ];
        $this->internalApiDocumentService
            ->updateDocumentType(DocumentsData::createUpdateDocumentTypeModel())
            ->shouldBeCalledOnce();

        $this->requestWithBody(BaseEnum::METHOD_PATCH, self::UPDATE_DOCUMENT_TYPE_PATH, $body);
        $this->assertEquals(204, $this->getStatusCode());
        $this->assertEquals(null, $this->getResponseContent());
    }

    public function testUpdateDocumentTypeThrowException()
    {
        $body = [
            'document_type_id' => 1,
            'sub_document_type_id' => 2,
        ];
        $this->internalApiDocumentService
            ->updateDocumentType(DocumentsData::createUpdateDocumentTypeModel())
            ->willThrow(new InternalApiInvalidDataException());
        $this->requestWithBody(BaseEnum::METHOD_PATCH, self::UPDATE_DOCUMENT_TYPE_PATH, $body);
        $this->assertEquals(400, $this->getStatusCode());
    }

    public function testUpdateDocumentTypeThrowNotFoundException()
    {
        $body = [
            'document_type_id' => 1,
            'sub_document_type_id' => 2,
        ];
        $this->internalApiDocumentService
            ->updateDocumentType(DocumentsData::createUpdateDocumentTypeModel())
            ->willThrow(new ResourceNotFoundException());
        $this->requestWithBody(BaseEnum::METHOD_PATCH, self::UPDATE_DOCUMENT_TYPE_PATH, $body);
        $this->assertEquals(404, $this->getStatusCode());
    }
}
