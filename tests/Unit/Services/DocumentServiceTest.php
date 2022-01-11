<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services;

use App\Enum\FolderEnum;
use App\Exception\InvalidDataException;
use App\Service\DocumentService;
use App\Tests\BaseApiTest;
use App\Tests\Mocks\Data\DocumentsData;
use Kyc\InternalApiBundle\Model\Request\Document\MergeDocumentModel;
use Kyc\InternalApiBundle\Service\DocumentService as InternalApiDocumentService;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Serializer\SerializerInterface;

class DocumentServiceTest extends BaseApiTest
{
    private ObjectProphecy $internalApiDocumentService;
    private ObjectProphecy $serializer;
    private DocumentService $documentService;

    public function setUp(): void
    {
        parent::setUp();
        $this->internalApiDocumentService = $this->prophesize(InternalApiDocumentService::class);
        $this->serializer = $this->prophesize(SerializerInterface::class);

        $this->documentService = new DocumentService(
            $this->serializer->reveal(),
            $this->internalApiDocumentService->reveal()
        );
    }

    public function testMergeDocumentsSuccess()
    {
        $mergeData = array_merge([FolderEnum::FOLDER_ID => 1], DocumentsData::MERGE_DOCUMENTS_BODY);
        $mergeDocumentModel = DocumentsData::createMergeDocumentModel(DocumentsData::MERGE_DOCUMENTS_BODY);
        $this->serializer->deserialize(json_encode($mergeData), MergeDocumentModel::class, 'json')
            ->shouldBeCalledOnce()
            ->willReturn($mergeDocumentModel);

        $this->internalApiDocumentService
            ->mergeDocuments($mergeDocumentModel)
            ->shouldBeCalledOnce();

        $this->documentService->mergeDocuments($mergeData);
    }

    public function testMergeDocumentsThrowsException()
    {
        $mergeData = array_merge([FolderEnum::FOLDER_ID => 1], DocumentsData::MERGE_DOCUMENTS_BODY, ['filename' => null]);
        $mergeDocumentModel = DocumentsData::createMergeDocumentModel(array_merge(DocumentsData::MERGE_DOCUMENTS_BODY, ['filename' => null]));
        $this->serializer->deserialize(json_encode($mergeData), MergeDocumentModel::class, 'json')
            ->shouldBeCalledOnce()
            ->willReturn($mergeDocumentModel);

        $this->internalApiDocumentService
            ->mergeDocuments($mergeDocumentModel)
            ->shouldBeCalledOnce()
            ->willThrow(new InvalidDataException('{"filename": "This value should not be blank."}'));

        $this->expectException(InvalidDataException::class);
        $this->expectExceptionMessage('{"filename": "This value should not be blank."}');

        $this->documentService->mergeDocuments($mergeData);
    }
}
