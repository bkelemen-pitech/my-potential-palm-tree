<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Enum\FolderEnum;
use App\Exception\InvalidDataException;
use App\Service\DocumentService;
use App\Tests\BaseApiTest;
use App\Tests\Mocks\Data\DocumentsData;
use Kyc\InternalApiBundle\Exception\InvalidDataException as InternalApiInvalidDataException;
use Kyc\InternalApiBundle\Service\DocumentService as InternalApiDocumentService;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Serializer\SerializerInterface;

class DocumentServiceTest extends BaseApiTest
{
    private ObjectProphecy $internalApiDocumentService;
    private $serializer;
    private DocumentService $documentService;

    public function setUp(): void
    {
        parent::setUp();
        $this->internalApiDocumentService = $this->prophesize(InternalApiDocumentService::class);
        $this->serializer = static::getContainer()->get(SerializerInterface::class);

        $this->documentService = new DocumentService(
            $this->serializer,
            $this->internalApiDocumentService->reveal()
        );
    }

    public function testMergeDocumentsSuccess()
    {
        $mergeData = array_merge([FolderEnum::FOLDER_ID => 1], DocumentsData::MERGE_DOCUMENTS_BODY);
        $mergeDocumentModel = DocumentsData::createMergeDocumentModel(DocumentsData::MERGE_DOCUMENTS_BODY);

        $this->internalApiDocumentService
            ->mergeDocuments($mergeDocumentModel)
            ->shouldBeCalledOnce();

        $this->documentService->mergeDocuments($mergeData);
    }

    public function testMergeDocumentsThrowsException()
    {
        $mergeData = array_merge([FolderEnum::FOLDER_ID => 1], DocumentsData::MERGE_DOCUMENTS_BODY, ['filename' => null]);
        $mergeDocumentModel = DocumentsData::createMergeDocumentModel(array_merge(DocumentsData::MERGE_DOCUMENTS_BODY, ['filename' => null]));

        $this->internalApiDocumentService
            ->mergeDocuments($mergeDocumentModel)
            ->shouldBeCalledOnce()
            ->willThrow(new InvalidDataException('{"filename": "This value should not be blank."}'));

        $this->expectException(InvalidDataException::class);
        $this->expectExceptionMessage('{"filename": "This value should not be blank."}');

        $this->documentService->mergeDocuments($mergeData);
    }

    public function testGetDocumentFieldsSuccess()
    {
        $documentFieldsModelRequest = DocumentsData::createDocumentFieldsRequestModel();
        $documentFieldsModelResponse = DocumentsData::createDocumentFieldsModelResponse();

        $this->internalApiDocumentService
            ->getDocumentFields($documentFieldsModelRequest)
            ->shouldBeCalledOnce()
            ->willReturn([$documentFieldsModelResponse]);

        $this->assertEquals(
            [$documentFieldsModelResponse],
            $this->documentService->getDocumentFields(['agency_id' => 1, 'document_type_id' => 1, 'person_type_id' => 1])
        );
    }

    public function testGetDocumentFieldsException()
    {
        $documentFieldsModelRequest = DocumentsData::createDocumentFieldsRequestModel();

        $this->internalApiDocumentService
            ->getDocumentFields($documentFieldsModelRequest)
            ->shouldBeCalledOnce()
            ->willThrow(new InternalApiInvalidDataException('Invalid request'));

        $this->expectException(InvalidDataException::class);
        $this->expectExceptionMessage('Invalid request');
        $this->documentService->getDocumentFields(['agency_id' => 1, 'document_type_id' => 1, 'person_type_id' => 1]);
    }

    public function testGetDocumentDataLogsSuccess()
    {
        $documentDataLogsModelRequest = DocumentsData::createDocumentDataLogsRequestModel();
        $documentDataLogsModelResponse = DocumentsData::createDocumentDataLogsModelResponse();

        $this->internalApiDocumentService
            ->getDocumentDataLogs($documentDataLogsModelRequest)
            ->shouldBeCalledOnce()
            ->willReturn($documentDataLogsModelResponse);

        $this->assertEquals(
            $documentDataLogsModelResponse,
            $this->documentService->getDocumentDataLogs(['administrator-id' => 1, 'document-ids' => [1, 2]])
        );
    }

    public function testGetDocumentDataLogsException()
    {
        $documentDataLogsModelRequest = DocumentsData::createDocumentDataLogsRequestModel();

        $this->internalApiDocumentService
            ->getDocumentDataLogs($documentDataLogsModelRequest)
            ->shouldBeCalledOnce()
            ->willThrow(new InternalApiInvalidDataException('Invalid request'));

        $this->expectException(InvalidDataException::class);
        $this->expectExceptionMessage('Invalid request');
        $this->documentService->getDocumentDataLogs(['administrator-id' => 1, 'document-ids' => [1, 2]]);
    }
}
