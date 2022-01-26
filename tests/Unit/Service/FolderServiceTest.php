<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Exception\ResourceNotFoundException;
use App\Security\JWTTokenAuthenticator;
use App\Service\FolderService;
use App\Service\ValidationService;
use App\Tests\BaseApiTest;
use App\Tests\Mocks\Data\FolderData;
use App\Tests\Mocks\Data\PersonData;
use Kyc\InternalApiBundle\Enum\BepremsEnum;
use Kyc\InternalApiBundle\Exception\InvalidDataException as InternalAPIInvalidDataException;
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

        $folderByIdModelResponse = FolderData::createFolderByIdModelResponse();
        $folderByIdModelResponse->setId($folderId);
        $folderByIdModelResponse->setWorkflowStatus(10300);

        $personModelResponse = PersonData::createPersonModelResponse();
        $personModelResponse->setFolderId($folderId);

        $updateStatusWorkflowModel = new UpdateStatusWorkflowModel();
        $updateStatusWorkflowModel
            ->setUserDossierId($folderId)
            ->setStatusWorkflow(10301)
            ->setAdministratorId(1);

        $this->internalApiFolderService->getFolderById($folderId)
            ->shouldBeCalledOnce()
            ->willReturn($folderByIdModelResponse);

        $this->internalApiFolderService->getPersonsByFolderId($folderId, $filters)
            ->shouldBeCalledOnce()
            ->willReturn([$personModelResponse]);

        $this->internalApiFolderService->updateStatusWorkflow($updateStatusWorkflowModel)->shouldBeCalledOnce();

        $this->authenticator->getLoggedUserData()->shouldBeCalledOnce()->willReturn(['userId' => 1]);

        $this->internalApiFolderService->assignAdministratorToFolder(1, $folderId)->shouldBeCalledOnce();

        $folderById = $this->folderService->getFolderData($folderId, $filters);
        $this->assertEquals($folderId, $folderById->getId());
        $this->assertEquals('Test login', $folderById->getLogin());
        $this->assertEquals(10301, $folderById->getWorkflowStatus());
        $this->assertEquals([$personModelResponse], $folderById->getPersons());
    }

    public function testGetFoldersDataWithoutPersons()
    {
        $folderId = 1;
        $filters = BepremsEnum::DEFAULT_FOLDER_BY_ID_FILTERS;

        $folderByIdModelResponse = FolderData::createFolderByIdModelResponse();
        $folderByIdModelResponse->setId($folderId);
        $folderByIdModelResponse->setWorkflowStatus(10302);

        $personModelResponse = PersonData::createPersonModelResponse();
        $personModelResponse->setFolderId($folderId);

        $updateStatusWorkflowModel = new UpdateStatusWorkflowModel();
        $updateStatusWorkflowModel
            ->setUserDossierId($folderId)
            ->setStatusWorkflow(10300)
            ->setAdministratorId(1);

        $this->internalApiFolderService->getFolderById($folderId)
            ->shouldBeCalledOnce()
            ->willReturn($folderByIdModelResponse);

        $this->internalApiFolderService->getPersonsByFolderId($folderId, $filters)
            ->shouldBeCalledOnce()
            ->willReturn([]);

        $this->internalApiFolderService->updateStatusWorkflow($updateStatusWorkflowModel)->shouldNotBeCalled();

        $this->authenticator->getLoggedUserData()->shouldNotBeCalled();

        $this->internalApiFolderService->assignAdministratorToFolder(1, $folderId)->shouldNotBeCalled();

        $folderById = $this->folderService->getFolderData($folderId, $filters);
        $this->assertEquals($folderId, $folderById->getId());
        $this->assertEquals('Test login', $folderById->getLogin());
        $this->assertEquals(10302, $folderById->getWorkflowStatus());
        $this->assertEquals([], $folderById->getPersons());
    }

    public function testGetFoldersDataWithNotExistentFolder()
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage('Folder with id 1 not found');

        $folderId = 1;
        $filters = BepremsEnum::DEFAULT_FOLDER_BY_ID_FILTERS;

        $folderByIdModelResponse = FolderData::createFolderByIdModelResponse();
        $folderByIdModelResponse->setId($folderId);
        $folderByIdModelResponse->setWorkflowStatus(10300);

        $personModelResponse = PersonData::createPersonModelResponse();
        $personModelResponse->setFolderId($folderId);

        $updateStatusWorkflowModel = new UpdateStatusWorkflowModel();
        $updateStatusWorkflowModel
            ->setUserDossierId($folderId)
            ->setStatusWorkflow(10300)
            ->setAdministratorId(1);

        $this->internalApiFolderService->getFolderById($folderId)
            ->shouldBeCalledOnce()
            ->willThrow(new KycResourceNotFoundException(sprintf('Folder with id %d not found', $folderId)));

        $this->internalApiFolderService->getPersonsByFolderId($folderId, $filters)->shouldNotBeCalled();

        $this->internalApiFolderService->updateStatusWorkflow($updateStatusWorkflowModel)->shouldNotBeCalled();

        $this->authenticator->getLoggedUserData()->shouldNotBeCalled();

        $this->internalApiFolderService->assignAdministratorToFolder(1, $folderId)->shouldNotBeCalled();

        $this->folderService->getFolderData($folderId, $filters);
    }

    public function testAssignAdministratorFails()
    {
        $folderId = 1;
        $filters = BepremsEnum::DEFAULT_FOLDER_BY_ID_FILTERS;

        $folderByIdModelResponse = new FolderByIdModelResponse();
        $folderByIdModelResponse
            ->setId($folderId)
            ->setLogin('Test')
            ->setWorkflowStatus(10300);

        $personModelResponse = new PersonModelResponse();
        $personModelResponse
            ->setFolderId($folderId)
            ->setFirstName('first')
            ->setLastName('last');

        $updateStatusWorkflowModel = new UpdateStatusWorkflowModel();
        $updateStatusWorkflowModel
            ->setUserDossierId($folderId)
            ->setStatusWorkflow(10301)
            ->setAdministratorId(1);

        $this->internalApiFolderService->getFolderById($folderId)
            ->shouldBeCalledOnce()
            ->willReturn($folderByIdModelResponse);

        $this->internalApiFolderService->getPersonsByFolderId($folderId, $filters)
            ->shouldBeCalledOnce()
            ->willReturn([$personModelResponse]);

        $this->internalApiFolderService->updateStatusWorkflow($updateStatusWorkflowModel)->shouldNotBeCalled();

        $this->authenticator->getLoggedUserData()->shouldBeCalledOnce()->willReturn(['userId' => 1]);

        $this->internalApiFolderService->assignAdministratorToFolder(1, $folderId)
            ->shouldBeCalledOnce()
            ->willThrow(new InternalAPIInvalidDataException('Third party error.'));

        $folderById = $this->folderService->getFolderData($folderId, $filters);
        $this->assertEquals($folderId, $folderById->getId());
        $this->assertEquals('Test', $folderById->getLogin());
        $this->assertEquals(10300, $folderById->getWorkflowStatus());
        $this->assertEquals([$personModelResponse], $folderById->getPersons());
    }

    /**
     * @dataProvider updateWorkflowStatusDataProvider
     */
    public function testUpdateWorkflowStatus(int $folderId, int $workflowStatus, ?int $administratorId)
    {
        if (!$administratorId) {
            $this->authenticator->getLoggedUserData()->shouldBeCalledOnce()->willReturn(['userId' => $administratorId]);
        }

        $updateStatusWorkflowModel = new UpdateStatusWorkflowModel();
        $updateStatusWorkflowModel
            ->setUserDossierId($folderId)
            ->setStatusWorkflow($workflowStatus)
            ->setAdministratorId($administratorId);

        $this->internalApiFolderService->updateStatusWorkflow($updateStatusWorkflowModel)->shouldBeCalledOnce();

        $this->folderService->updateWorkflowStatus($folderId, ['workflowStatus' => $workflowStatus], $administratorId);
    }

    public function updateWorkflowStatusDataProvider(): array
    {
        return [
            [1, 10301, 1],
            [2, 10350, null],
        ];
    }
}
