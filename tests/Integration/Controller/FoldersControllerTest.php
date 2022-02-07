<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Exception\InvalidDataException;
use App\Exception\ResourceNotFoundException;
use App\Model\Request\BaseFolderFiltersModel;
use App\Service\DocumentService;
use App\Service\PersonService;
use App\Tests\BaseApiTest;
use App\Tests\Enum\AdministratorEnum;
use App\Tests\Enum\BaseEnum;
use App\Tests\Enum\FolderEnum;
use App\Tests\Enum\PersonEnum;
use App\Tests\Mocks\Data\DocumentsData;
use App\Tests\Mocks\Data\FolderData;
use App\Tests\Mocks\Data\PersonData;
use Kyc\InternalApiBundle\Model\Request\Administrator\AssignedAdministratorFilterModel;
use Kyc\InternalApiBundle\Model\Request\Folder\UpdateStatusWorkflowModel;
use Kyc\InternalApiBundle\Model\Request\WorkflowStatusHistory\WorkflowStatusHistoryModel;
use Kyc\InternalApiBundle\Model\Response\Folder\AssignedAdministratorModelResponse;
use Kyc\InternalApiBundle\Model\Response\Folder\FolderModelResponse;
use Kyc\InternalApiBundle\Model\Response\WorkflowStatusHistory\WorkflowStatusHistoryModelResponse;
use Kyc\InternalApiBundle\Service\FolderService as InternalApiFolderService;
use Kyc\InternalApiBundle\Service\DocumentService as InternalApiDocumentService;
use Kyc\InternalApiBundle\Exception\InvalidDataException as InternalApiInvalidDataException;
use Kyc\InternalApiBundle\Exception\ResourceNotFoundException as InternalApiResourceNotFoundException;
use Kyc\InternalApiBundle\Service\WorkflowStatusHistoryService;
use Prophecy\Prophecy\ObjectProphecy;

class FoldersControllerTest extends BaseApiTest
{
    public const GET_FOLDERS = 'api/v1/folders';
    public const GET_FOLDER = 'api/v1/folders/1';
    public const FOLDER_GET_DOCUMENTS = 'api/v1/folders/1/documents';
    public const FOLDER_ADD_PERSON = 'api/v1/folders/1/add-person';
    public const FOLDER_ASSIGN_DOCUMENT_TO_PERSON = 'api/v1/folders/1/persons/6196610f9d67/documents/6184c9672f420';
    public const FOLDER_ASSIGN_DOCUMENT_TO_PERSON_NO_DOCUMENT = 'api/v1/folders/1/persons/6196610f9d67/documents/';
    public const FOLDER_MERGE_DOCUMENTS = 'api/v1/folders/1/document/merge';
    public const FOLDER_MERGE_DOCUMENTS_NO_FOLDER_ID = 'api/v1/folders//document/merge';
    public const FOLDER_UPDATE_WORKFLOW_STATUS = 'api/v1/folders/1/update-workflow-status';
    public const FOLDER_WORKFLOW_STATUS_HISTORY = 'api/v1/folders/1/workflow-status-history';

    protected ObjectProphecy $internalApiFolderService;
    protected ObjectProphecy $internalApiDocumentService;
    protected ObjectProphecy $internalApiWorkflowStatusHistoryService;
    protected ObjectProphecy $documentService;
    protected ObjectProphecy $personService;

    public function setUp(): void
    {
        parent::setUp();
        $this->internalApiFolderService = $this->prophesize(InternalApiFolderService::class);
        $this->internalApiDocumentService = $this->prophesize(InternalApiDocumentService::class);
        $this->internalApiWorkflowStatusHistoryService = $this->prophesize(WorkflowStatusHistoryService::class);
        $this->documentService = $this->prophesize(DocumentService::class);
        $this->personService = $this->prophesize(PersonService::class);
        static::getContainer()->set(InternalApiFolderService::class, $this->internalApiFolderService->reveal());
        static::getContainer()->set(InternalApiDocumentService::class, $this->internalApiDocumentService->reveal());
        static::getContainer()->set(
            WorkflowStatusHistoryService::class,
            $this->internalApiWorkflowStatusHistoryService->reveal()
        );
        static::getContainer()->set(DocumentService::class, $this->documentService->reveal());
        static::getContainer()->set(PersonService::class, $this->personService->reveal());
    }

    public function getFoldersParametersWith1ApiCall(): array
    {
        return FolderData::GET_FOLDERS_PARAMETERS_WITH_1_API_CALL;
    }

    /**
     * @dataProvider getFoldersParametersWith1ApiCall
     */
    public function testGetFoldersWith1ApiCall(array $queryParameters, int $viewCriteriaResponse)
    {
        $folderFilterModel = (new BaseFolderFiltersModel())
            ->setTextSearchFields('date_of_birth')
            ->setFilters(
                'workflow_status:10301,workflow_status:10302,workflow_status:10303,workflow_status:10304,person_type_id:1'
            )
            ->setAdministratorId(1);
        $folderModelResponse1 = (new FolderModelResponse())
            ->setFolderId(1)
            ->setFolder('1a')
            ->setFirstName('First Name 1')
            ->setLastName('Last Name 1')
            ->setDateOfBirth('2020-02-02T00:00:00+00:00')
            ->setSubscription(10)
            ->setAssignedTo('Admin1');
        $folderModelResponse2 = (new FolderModelResponse())
            ->setFolderId(2)
            ->setFolder('2a')
            ->setFirstName('First Name 2')
            ->setLastName('Last Name 2')
            ->setDateOfBirth('2020-02-02T00:00:00+00:00')
            ->setSubscription(20)
            ->setAssignedTo('Admin1');

        $this->internalApiFolderService->getFolders($folderFilterModel)
            ->shouldBeCalledOnce()
            ->willReturn([
                FolderEnum::FOLDERS => [
                    $folderModelResponse1,
                    $folderModelResponse2,
                ],
                FolderEnum::META => [
                    FolderEnum::TOTAL => 2,
                ],
            ]);

        $this->requestWithBody(
            BaseEnum::METHOD_GET,
            self::GET_FOLDERS,
            [],
            [],
            true,
            $queryParameters,
        );

        $this->assertEquals(200, $this->getStatusCode());

        $this->assertEquals(
            [
                FolderEnum::FOLDERS => [
                    $folderModelResponse1->toArray(),
                    $folderModelResponse2->toArray(),
                ],
                FolderEnum::META => [
                    FolderEnum::TOTAL => 2,
                    FolderEnum::VIEW_CRITERIA => $viewCriteriaResponse,
                ],
            ],
            $this->getResponseContent()
        );
    }

    public function getFoldersParametersWith2ApiCalls(): array
    {
        return FolderData::GET_FOLDERS_PARAMETERS_WITH_2_API_CALLS;
    }

    /**
     * @dataProvider getFoldersParametersWith2ApiCalls
     */
    public function testGetFoldersWith2ApiCalls(string $filters, array $queryParameters, int $viewCriteriaResponse)
    {
        $folderFilterModel = (new BaseFolderFiltersModel())
            ->setTextSearchFields('date_of_birth')
            ->setFilters($filters);
        $folderModelResponse1 = (new FolderModelResponse())
            ->setFolderId(1)
            ->setFolder('1a')
            ->setFirstName('First Name 1')
            ->setLastName('Last Name 1')
            ->setDateOfBirth('2020-02-02T00:00:00+00:00')
            ->setSubscription(10);
        $folderModelResponse2 = (new FolderModelResponse())
            ->setFolderId(2)
            ->setFolder('2a')
            ->setFirstName('First Name 2')
            ->setLastName('Last Name 2')
            ->setDateOfBirth('2020-02-02T00:00:00+00:00')
            ->setSubscription(20);

        $this->internalApiFolderService->getFolders($folderFilterModel)
            ->shouldBeCalledOnce()
            ->willReturn([
                FolderEnum::FOLDERS => [
                    $folderModelResponse1,
                    $folderModelResponse2,
                ],
                FolderEnum::META => [
                    FolderEnum::TOTAL => 2,
                ],
            ]);

        $filterModel = (new AssignedAdministratorFilterModel())->setUserDossierIds([1, 2]);

        $resultAssignedAdministratorModelResponse1 = (new AssignedAdministratorModelResponse())
            ->setAdministratorId(1)
            ->setStatus(AdministratorEnum::STATUS_IN_PROGRESS)
            ->setUsername('Admin1')
            ->setFolderId(1);

        $resultAssignedAdministratorModelResponse2 = (new AssignedAdministratorModelResponse())
            ->setAdministratorId(2)
            ->setStatus(AdministratorEnum::STATUS_IN_PROGRESS)
            ->setUsername('Admin2')
            ->setFolderId(2);

        $this->internalApiFolderService->getAssignedAdministrators($filterModel)
            ->shouldBeCalledOnce()
            ->willReturn([
                $resultAssignedAdministratorModelResponse1,
                $resultAssignedAdministratorModelResponse2,
            ]);
        $this->requestWithBody(
            BaseEnum::METHOD_GET,
            self::GET_FOLDERS,
            [],
            [],
            true,
            $queryParameters,
        );

        $this->assertEquals(200, $this->getStatusCode());

        $folderModelResponse1->setAssignedTo('Admin1');
        $folderModelResponse2->setAssignedTo('Admin2');

        $this->assertEquals(
            [
                FolderEnum::FOLDERS => [
                    $folderModelResponse1->toArray(),
                    $folderModelResponse2->toArray(),
                ],
                FolderEnum::META => [
                    FolderEnum::TOTAL => 2,
                    FolderEnum::VIEW_CRITERIA => $viewCriteriaResponse,
                ],
            ],
            $this->getResponseContent()
        );
    }

    public function getFoldersParametersWith3ApiCalls(): array
    {
        return FolderData::GET_FOLDERS_PARAMETERS_WITH_3_API_CALLS;
    }

    /**
     * @dataProvider getFoldersParametersWith3ApiCalls
     */
    public function testGetFoldersWith3ApiCalls(string $filters, array $queryParameters)
    {
        $folderFilterModel1 = (new BaseFolderFiltersModel())
            ->setTextSearchFields('date_of_birth')
            ->setFilters($filters)
            ->setAdministratorId(1);
        $folderFilterModel2 = (new BaseFolderFiltersModel())
            ->setTextSearchFields('date_of_birth')
            ->setFilters($filters);
        $folderModelResponse1 = (new FolderModelResponse())
            ->setFolderId(1)
            ->setFolder('1a')
            ->setFirstName('First Name 1')
            ->setLastName('Last Name 1')
            ->setDateOfBirth('2020-02-02T00:00:00+00:00')
            ->setSubscription(10)
            ->setAssignedTo('Admin1');
        $folderModelResponse2 = (new FolderModelResponse())
            ->setFolderId(2)
            ->setFolder('2a')
            ->setFirstName('First Name 2')
            ->setLastName('Last Name 2')
            ->setDateOfBirth('2020-02-02T00:00:00+00:00')
            ->setSubscription(20)
            ->setAssignedTo('Admin2');

        $this->internalApiFolderService->getFolders($folderFilterModel1)
            ->shouldBeCalledOnce()
            ->willReturn([
                FolderEnum::FOLDERS => [],
                FolderEnum::META => [
                    FolderEnum::TOTAL => 0,
                ],
            ]);

        $this->internalApiFolderService->getFolders($folderFilterModel2)
            ->shouldBeCalledOnce()
            ->willReturn([
                FolderEnum::FOLDERS => [
                    $folderModelResponse1,
                    $folderModelResponse2,
                ],
                FolderEnum::META => [
                    FolderEnum::TOTAL => 2,
                ],
            ]);

        $filterModel = (new AssignedAdministratorFilterModel())->setUserDossierIds([1, 2]);

        $resultAssignedAdministratorModelResponse1 = (new AssignedAdministratorModelResponse())
            ->setAdministratorId(1)
            ->setStatus(AdministratorEnum::STATUS_IN_PROGRESS)
            ->setUsername('Admin1')
            ->setFolderId(1);

        $resultAssignedAdministratorModelResponse2 = (new AssignedAdministratorModelResponse())
            ->setAdministratorId(2)
            ->setStatus(AdministratorEnum::STATUS_IN_PROGRESS)
            ->setUsername('Admin2')
            ->setFolderId(2);

        $this->internalApiFolderService->getAssignedAdministrators($filterModel)
            ->shouldBeCalledOnce()
            ->willReturn([
                $resultAssignedAdministratorModelResponse1,
                $resultAssignedAdministratorModelResponse2,
            ]);
        $this->requestWithBody(
            BaseEnum::METHOD_GET,
            self::GET_FOLDERS,
            [],
            [],
            true,
            $queryParameters,
        );

        $this->assertEquals(200, $this->getStatusCode());

        $folderModelResponse1->setAssignedTo('Admin1');
        $folderModelResponse2->setAssignedTo('Admin2');

        $this->assertEquals(
            [
                FolderEnum::FOLDERS => [
                    $folderModelResponse1->toArray(),
                    $folderModelResponse2->toArray(),
                ],
                FolderEnum::META => [
                    FolderEnum::TOTAL => 2,
                    FolderEnum::VIEW_CRITERIA => 2,
                ],
            ],
            $this->getResponseContent()
        );
    }

    public function testGetFolderById()
    {
        $this->internalApiFolderService->getFolderById(1)
            ->shouldBeCalledOnce()
            ->willReturn(FolderData::createFolderByIdModelResponse());
        $this->internalApiFolderService
            ->getPersonsByFolderId(1, FolderData::GET_FOLDER_BY_ID_ORDER_FILTERS)
            ->shouldBeCalledOnce()
            ->willReturn(PersonData::getFolderPersonsModelResponseByIdTestData());
        $this->requestWithBody(BaseEnum::METHOD_GET, self::GET_FOLDER);

        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals(FolderData::getFolderByIdExpectedData(), $this->getResponseContent());
    }

    public function testGetFolderByIdNotFound()
    {
        $this->internalApiFolderService->getFolderById(1)->willThrow(new ResourceNotFoundException());
        $this->internalApiFolderService
            ->getPersonsByFolderId(1, FolderData::GET_FOLDER_BY_ID_ORDER_FILTERS)
            ->shouldNotBeCalled();
        $this->requestWithBody(BaseEnum::METHOD_GET, self::GET_FOLDER);

        $this->assertEquals(404, $this->getStatusCode());
    }

    public function testAddPersonOk()
    {
        $body = [
            'personTypeId' => 1,
            'agencyId' => 709,
        ];

        $this->personService
            ->addPerson(1, $body)
            ->shouldBeCalledOnce()
            ->willReturn(PersonData::DEFAULT_PERSON_UID_TEST_DATA);
        $this->requestWithBody(BaseEnum::METHOD_POST, self::FOLDER_ADD_PERSON, $body);

        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals(
            [PersonEnum::PERSON_UID => PersonData::DEFAULT_PERSON_UID_TEST_DATA],
            $this->getResponseContent()
        );
    }

    public function testAddPersonEmptyBodyShouldThrowException()
    {
        $this->personService
            ->addPerson(1, [])
            ->shouldBeCalledOnce()
            ->willThrow(new InvalidDataException());

        $this->requestWithBody(BaseEnum::METHOD_POST, self::FOLDER_ADD_PERSON, []);
        $this->assertEquals(400, $this->getStatusCode());
    }

    public function testAssignDocumentOk()
    {
        $requestData = ["folderId" => 1, "personUid" => "6196610f9d67", "documentUid" => "6184c9672f420"];
        $this->personService
            ->assignDocument($requestData)
            ->shouldBeCalledOnce();

        $this->requestWithBody(BaseEnum::METHOD_PUT, self::FOLDER_ASSIGN_DOCUMENT_TO_PERSON);

        $this->assertEquals(204, $this->getStatusCode());
        $this->assertEquals(null, $this->getResponseContent());
    }

    public function testAssignDocumentNotFoundException()
    {
        $this->requestWithBody(BaseEnum::METHOD_PUT, self::FOLDER_ASSIGN_DOCUMENT_TO_PERSON_NO_DOCUMENT);
        $this->assertEquals(404, $this->getStatusCode());
    }

    public function testDocumentsByFolderIdCalled()
    {
        $this->internalApiDocumentService->getDocumentsByFolderId(1)
            ->shouldBeCalledOnce()
            ->willReturn(DocumentsData::getTestDocumentByUidExpectedData());

        $this->requestWithBody(BaseEnum::METHOD_GET, self::FOLDER_GET_DOCUMENTS);

        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals(DocumentsData::getTestDocumentByUidExpectedData(), $this->getResponseContent());
    }

    public function testDocumentsByFolderIdThrowsException()
    {
        $this->internalApiDocumentService->getDocumentsByFolderId(1)
            ->shouldBeCalledOnce()
            ->willThrow(new InternalApiInvalidDataException());

        $this->requestWithBody(BaseEnum::METHOD_GET, self::FOLDER_GET_DOCUMENTS);
        $this->assertEquals(400, $this->getStatusCode());
    }

    public function testMergeDocumentsOk()
    {
        $this->documentService
            ->mergeDocuments(array_merge([FolderEnum::FOLDER_ID => 1], DocumentsData::MERGE_DOCUMENTS_BODY))
            ->shouldBeCalledOnce();

        $this->requestWithBody(
            BaseEnum::METHOD_POST,
            self::FOLDER_MERGE_DOCUMENTS,
            DocumentsData::MERGE_DOCUMENTS_BODY
        );

        $this->assertEquals(204, $this->getStatusCode());
        $this->assertEquals(null, $this->getResponseContent());
    }

    public function testMergeDocumentsNotFoundException()
    {
        $this->requestWithBody(BaseEnum::METHOD_POST, self::FOLDER_MERGE_DOCUMENTS_NO_FOLDER_ID);
        $this->assertEquals(404, $this->getStatusCode());
    }

    public function testUpdateWorkflowStatusSuccess()
    {
        $updateStatusWorkflowModel = new UpdateStatusWorkflowModel();
        $updateStatusWorkflowModel
            ->setUserDossierId(1)
            ->setStatusWorkflow(10350)
            ->setAdministratorId(1);

        $this->internalApiFolderService->updateStatusWorkflow($updateStatusWorkflowModel)->shouldBeCalledOnce();

        $this->requestWithBody(BaseEnum::METHOD_POST, self::FOLDER_UPDATE_WORKFLOW_STATUS, ['workflowStatus' => 10350]);

        $this->assertEquals(204, $this->getStatusCode());
        $this->assertEquals(null, $this->getResponseContent());
    }

    public function testUpdateWorkflowStatusInvalidRequestException()
    {
        $updateStatusWorkflowModel = new UpdateStatusWorkflowModel();
        $updateStatusWorkflowModel
            ->setUserDossierId(1)
            ->setAdministratorId(1);

        $this->internalApiFolderService
            ->updateStatusWorkflow($updateStatusWorkflowModel)
            ->shouldBeCalledOnce()
            ->willThrow(
                new InternalApiInvalidDataException(
                    json_encode(['statusWorkflow' => 'This value should not be blank.'])
                )
            );

        $this->requestWithBody(BaseEnum::METHOD_POST, self::FOLDER_UPDATE_WORKFLOW_STATUS, []);

        $this->assertEquals(400, $this->getStatusCode());
        $expectedErrorMessage = [
            'statusWorkflow' => 'This value should not be blank.',
        ];
        $this->assertEquals(
            $this->buildExceptionResponse(400, $expectedErrorMessage, json_encode($expectedErrorMessage)),
            $this->getResponseContent()
        );
    }

    public function testUpdateWorkflowStatusNotFoundException()
    {
        $updateStatusWorkflowModel = new UpdateStatusWorkflowModel();
        $updateStatusWorkflowModel
            ->setUserDossierId(1)
            ->setStatusWorkflow(10300)
            ->setAdministratorId(1);

        $this->internalApiFolderService
            ->updateStatusWorkflow($updateStatusWorkflowModel)
            ->shouldBeCalledOnce()
            ->willThrow(new InternalApiResourceNotFoundException('Folder with id 1 not found'));

        $this->requestWithBody(BaseEnum::METHOD_POST, self::FOLDER_UPDATE_WORKFLOW_STATUS, ['workflowStatus' => 10300]);

        $this->assertEquals(404, $this->getStatusCode());

        $this->assertEquals(
            $this->buildExceptionResponse(404, null, 'Folder with id 1 not found'),
            $this->getResponseContent()
        );
    }

    public function testGetWorkflowStatusHistorySuccess()
    {
        $workflowStatusHistoryRequest = new WorkflowStatusHistoryModel();
        $workflowStatusHistoryRequest
            ->setFolderId(1)
            ->setAdministratorId(1);

        $workflowStatusHistoryModelResponse = new WorkflowStatusHistoryModelResponse();
        $workflowStatusHistoryModelResponse
            ->setAdministratorId(1)
            ->setWorkflowStatus(10350)
            ->setAdministratorId(1)
            ->setFolderId(1);

        $this->internalApiWorkflowStatusHistoryService
            ->getWorkflowStatusHistory($workflowStatusHistoryRequest)
            ->shouldBeCalledOnce()
            ->willReturn([$workflowStatusHistoryModelResponse]);

        $this->requestWithBody(
            BaseEnum::METHOD_GET,
            self::FOLDER_WORKFLOW_STATUS_HISTORY,
            [],
            [],
            true,
            ['administrator-id' => 1]
        );

        $this->assertEquals(200, $this->getStatusCode());
        $response = [
            'workflowStatusHistory' => [
                [
                    'folderId' => $workflowStatusHistoryModelResponse->getFolderId(),
                    'administratorId' => $workflowStatusHistoryModelResponse->getAdministratorId(),
                    'agentId' => $workflowStatusHistoryModelResponse->getAgentId(),
                    'workflowStatus' => $workflowStatusHistoryModelResponse->getWorkflowStatus(),
                    'createdAt' => $workflowStatusHistoryModelResponse->getCreatedAt(),
                    'updatedAt' => $workflowStatusHistoryModelResponse->getUpdatedAt(),
                ],
            ],
        ];
        $this->assertEquals($response, $this->getResponseContent());
    }

    public function testGetWorkflowStatusHistoryException()
    {
        $workflowStatusHistoryRequest = new WorkflowStatusHistoryModel();
        $workflowStatusHistoryRequest
            ->setFolderId(1)
            ->setAdministratorId(1);

        $this->internalApiWorkflowStatusHistoryService
            ->getWorkflowStatusHistory($workflowStatusHistoryRequest)
            ->shouldBeCalledOnce()
            ->willThrow(new InternalApiInvalidDataException('Invalid request'));

        $this->requestWithBody(
            BaseEnum::METHOD_GET,
            self::FOLDER_WORKFLOW_STATUS_HISTORY,
            [],
            [],
            true,
            ['administrator-id' => 1]
        );

        $this->assertEquals(400, $this->getStatusCode());
        $this->assertEquals(
            $this->buildExceptionResponse(400, null, 'Invalid request'),
            $this->getResponseContent()
        );
    }
}
