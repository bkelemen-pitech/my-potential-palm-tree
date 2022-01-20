<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Enum\FolderEnum;
use App\Enum\UserEnum;
use App\Exception\ResourceNotFoundException;
use App\Security\JWTTokenAuthenticator;
use App\Service\FolderService;
use App\Service\ValidationService;
use App\Tests\BaseApiTest;
use Kyc\InternalApiBundle\Enum\BepremsEnum;
use Kyc\InternalApiBundle\Model\Request\Folder\UpdateStatusWorkflowModel;
use Kyc\InternalApiBundle\Model\Response\Folder\FolderByIdModelResponse;
use Kyc\InternalApiBundle\Model\Response\Person\PersonModelResponse;
use Kyc\InternalApiBundle\Service\DocumentService as InternalApiDocumentService;
use Kyc\InternalApiBundle\Service\FolderService as InternalApiFolderService;
use Kyc\InternalApiBundle\Exception\ResourceNotFoundException as KycResourceNotFoundException;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class FolderServiceTest extends BaseApiTest
{
    private ObjectProphecy $serializer;
    private ObjectProphecy $validationService;
    private ObjectProphecy $internalApiFolderService;
    private ObjectProphecy $internalApiDocumentService;
    private ObjectProphecy $logger;
    private ObjectProphecy $authenticator;
    private FolderService $folderService;

    public function setUp(): void
    {
        parent::setUp();
        $this->serializer = $this->prophesize(SerializerInterface::class);
        $this->validationService = $this->prophesize(ValidationService::class);
        $this->internalApiFolderService = $this->prophesize(InternalApiFolderService::class);
        $this->internalApiDocumentService = $this->prophesize(InternalApiDocumentService::class);
        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->authenticator = $this->prophesize(JWTTokenAuthenticator::class);

        $this->folderService = new FolderService(
            $this->serializer->reveal(),
            $this->validationService->reveal(),
            $this->internalApiFolderService->reveal(),
            $this->internalApiDocumentService->reveal(),
            $this->logger->reveal(),
            $this->authenticator->reveal()
        );
    }

    public function testGetFoldersData()
    {
        $folderId = 1;
        $filters = BepremsEnum::DEFAULT_FOLDER_BY_ID_FILTERS;

        $folderByIdModelResponse = new FolderByIdModelResponse();
        $folderByIdModelResponse
            ->setId($folderId)
            ->setLogin('Test')
            ->setWorkflowStatus(FolderEnum::WORKFLOW_STATUS_PROCESSED_BY_WEBHELP);

        $personModelResponse = new PersonModelResponse();
        $personModelResponse
            ->setFolderId($folderId)
            ->setFirstName('first')
            ->setLastName('last');

        $updateStatusWorkflowModel = new UpdateStatusWorkflowModel();
        $updateStatusWorkflowModel
            ->setUserDossierId($folderId)
            ->setStatusWorkflow(FolderEnum::WORKFLOW_STATUS_IN_PROGRESS_BY_WEBHELP)
            ->setAdministratorId(1);

        $this->internalApiFolderService->getFolderById($folderId)
            ->shouldBeCalledOnce()
            ->willReturn($folderByIdModelResponse);

        $this->internalApiFolderService->getPersonsByFolderId($folderId, $filters)
            ->shouldBeCalledOnce()
            ->willReturn([$personModelResponse]);

        $this->internalApiFolderService->updateStatusWorkflow($updateStatusWorkflowModel)->shouldBeCalledOnce();

        $this->authenticator->getLoggedUserData()->shouldBeCalledOnce()->willReturn([UserEnum::USER_ID => 1]);

        $folderById = $this->folderService->getFolderData($folderId, $filters);
        $this->assertEquals($folderId, $folderById->getId());
        $this->assertEquals('Test', $folderById->getLogin());
        $this->assertEquals(FolderEnum::WORKFLOW_STATUS_IN_PROGRESS_BY_WEBHELP, $folderById->getWorkflowStatus());
        $this->assertEquals([$personModelResponse], $folderById->getPersons());
    }

    public function testGetFoldersDataWithoutPersons()
    {
        $folderId = 1;
        $filters = BepremsEnum::DEFAULT_FOLDER_BY_ID_FILTERS;

        $folderByIdModelResponse = new FolderByIdModelResponse();
        $folderByIdModelResponse
            ->setId($folderId)
            ->setLogin('Test')
            ->setWorkflowStatus(FolderEnum::WORKFLOW_STATUS_IN_SUPERVISED_BY_WEBHELP);

        $personModelResponse = new PersonModelResponse();
        $personModelResponse
            ->setFolderId($folderId)
            ->setFirstName('first')
            ->setLastName('last');

        $updateStatusWorkflowModel = new UpdateStatusWorkflowModel();
        $updateStatusWorkflowModel
            ->setUserDossierId($folderId)
            ->setStatusWorkflow(FolderEnum::WORKFLOW_STATUS_PROCESSED_BY_WEBHELP)
            ->setAdministratorId(1);

        $this->internalApiFolderService->getFolderById($folderId)
            ->shouldBeCalledOnce()
            ->willReturn($folderByIdModelResponse);

        $this->internalApiFolderService->getPersonsByFolderId($folderId, $filters)
            ->shouldBeCalledOnce()
            ->willReturn([]);

        $this->internalApiFolderService->updateStatusWorkflow($updateStatusWorkflowModel)->shouldNotBeCalled();

        $this->authenticator->getLoggedUserData()->shouldNotBeCalled();

        $folderById = $this->folderService->getFolderData($folderId, $filters);
        $this->assertEquals($folderId, $folderById->getId());
        $this->assertEquals('Test', $folderById->getLogin());
        $this->assertEquals(FolderEnum::WORKFLOW_STATUS_IN_SUPERVISED_BY_WEBHELP, $folderById->getWorkflowStatus());
        $this->assertEquals([], $folderById->getPersons());
    }

    public function testGetFoldersDataWithNotExistentFolder()
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage('Folder with id 1 not found');

        $folderId = 1;
        $filters = BepremsEnum::DEFAULT_FOLDER_BY_ID_FILTERS;

        $folderByIdModelResponse = new FolderByIdModelResponse();
        $folderByIdModelResponse
            ->setId($folderId)
            ->setLogin('Test')
            ->setWorkflowStatus(FolderEnum::WORKFLOW_STATUS_PROCESSED_BY_WEBHELP);

        $personModelResponse = new PersonModelResponse();
        $personModelResponse
            ->setFolderId($folderId)
            ->setFirstName('first')
            ->setLastName('last');

        $updateStatusWorkflowModel = new UpdateStatusWorkflowModel();
        $updateStatusWorkflowModel
            ->setUserDossierId($folderId)
            ->setStatusWorkflow(FolderEnum::WORKFLOW_STATUS_PROCESSED_BY_WEBHELP)
            ->setAdministratorId(1);

        $this->internalApiFolderService->getFolderById($folderId)
            ->shouldBeCalledOnce()
            ->willThrow(new KycResourceNotFoundException(sprintf('Folder with id %d not found', $folderId)));

        $this->internalApiFolderService->getPersonsByFolderId($folderId, $filters)->shouldNotBeCalled();

        $this->internalApiFolderService->updateStatusWorkflow($updateStatusWorkflowModel)->shouldNotBeCalled();

        $this->authenticator->getLoggedUserData()->shouldNotBeCalled();

        $this->folderService->getFolderData($folderId, $filters);
    }
}
