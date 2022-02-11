<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\WorkflowStatusHistoryService;
use App\Tests\BaseApiTest;
use Kyc\InternalApiBundle\Model\Request\WorkflowStatusHistory\WorkflowStatusHistoryModel;
use Kyc\InternalApiBundle\Model\Response\WorkflowStatusHistory\WorkflowStatusHistoryModelResponse;
use Kyc\InternalApiBundle\Service\WorkflowStatusHistoryService as InternalApiWorkflowStatusHistoryService;
use Prophecy\Prophecy\ObjectProphecy;

class WorkflowStatusHistoryServiceTest extends BaseApiTest
{
    protected ObjectProphecy $internalApiWorkflowStatusHistoryService;
    protected WorkflowStatusHistoryService $workflowStatusHistoryService;

    public function setUp(): void
    {
        $this->internalApiWorkflowStatusHistoryService = $this->prophesize(InternalApiWorkflowStatusHistoryService::class);
        $this->workflowStatusHistoryService = new WorkflowStatusHistoryService($this->internalApiWorkflowStatusHistoryService->reveal());
    }

    public function testGetWorkflowStatusHistory()
    {
        $workflowStatusHistoryRequest = new WorkflowStatusHistoryModel();
        $workflowStatusHistoryRequest
            ->setFolderId(1)
            ->setAdministratorId(1)
            ->setFilters(['workflow_status' => [10350]]);

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

        $this->assertEquals(
            [$workflowStatusHistoryModelResponse],
            $this->workflowStatusHistoryService->getWorkflowStatusHistory(1, 1, ['workflow_status' => [10350]])
        );
    }
}
