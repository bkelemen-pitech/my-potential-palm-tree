<?php

declare(strict_types=1);

namespace App\Service;

use Kyc\InternalApiBundle\Model\Request\WorkflowStatusHistory\WorkflowStatusHistoryModel;
use Kyc\InternalApiBundle\Service\WorkflowStatusHistoryService as InternalApiWorkflowStatusHistoryService;

class WorkflowStatusHistoryService
{
    protected InternalApiWorkflowStatusHistoryService $internalApiWorkflowStatusHistoryService;

    public function __construct(
        InternalApiWorkflowStatusHistoryService $internalApiWorkflowStatusHistoryService
    ) {
        $this->internalApiWorkflowStatusHistoryService = $internalApiWorkflowStatusHistoryService;
    }
    
    public function getWorkflowStatusHistory(int $folderId, ?int $administratorId, ?array $filters): array
    {
        $workflowStatusHistoryModel = new WorkflowStatusHistoryModel();
        $workflowStatusHistoryModel
            ->setFolderId($folderId)
            ->setAdministratorId($administratorId)
            ->setFilters($filters);

        return $this->internalApiWorkflowStatusHistoryService->getWorkflowStatusHistory($workflowStatusHistoryModel);
    }
}
