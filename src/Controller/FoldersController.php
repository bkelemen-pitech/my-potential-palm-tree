<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\BepremsEnum;
use App\Enum\FolderEnum;
use App\Enum\PersonEnum;
use App\Exception\ApiException;
use App\Exception\ResourceNotFoundException;
use App\Service\FolderService;
use App\Service\PersonService;
use App\Service\DocumentService;
use Kyc\InternalApiBundle\Exception\ResourceNotFoundException as InternalApiResourceNotFound;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/folders", name="api_v1_folders_")
 */
class FoldersController extends AbstractController
{
    protected PersonService $personService;
    protected FolderService $folderService;
    protected DocumentService $documentService;

    public function __construct(
        PersonService $personService,
        FolderService $folderService,
        DocumentService $documentService
    ) {
        $this->personService = $personService;
        $this->folderService = $folderService;
        $this->documentService = $documentService;
    }

    /**
     * @Route("/", name="get_folders", methods="GET")
     */
    public function getFolders(Request $request): JsonResponse
    {
        try {
            $folders = $this->folderService->getFolders($request->query->all());
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }

        return $this->json($folders);
    }

    /**
     * @Route("/{id}", name="get_by_id", methods="GET")
     */
    public function getFolderById(int $id): JsonResponse
    {
        try {
            $folderData = $this->folderService->getFolderData($id, BepremsEnum::DEFAULT_FOLDER_BY_ID_FILTERS);
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_NOT_FOUND, $exception->getMessage());
        }

        return $this->json($folderData);
    }

    /**
     * @Route("/{id}/documents", name="get_documents", methods="GET")
     */
    public function getDocuments(int $id, FolderService $folderService): JsonResponse
    {
        try {
            $documents = $folderService->getDocuments($id);
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }

        return $this->json($documents);
    }

    /**
     * @Route("/{id}/add-person", name="add_person", methods="POST")
     */
    public function createPerson(int $id, Request $request): JsonResponse
    {
        try {
            $personUid = $this->personService->addPerson($id, $request->toArray());
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }

        return $this->json([PersonEnum::PERSON_UID => $personUid]);
    }

    /**
     * @Route("/{folderId}/persons/{personUid}/documents/{documentUid}", name="assign_document_to_person", methods="PUT")
     */
    public function assignDocument(int $folderId, Request $request): JsonResponse
    {
        try {
            $this->personService->assignDocument(array_merge($request->attributes->get('_route_params'), [FolderEnum::FOLDER_ID => $folderId]));
        } catch (ResourceNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/{folderId}/document/merge", name="merge_documents", methods="POST")
     */
    public function mergeDocuments(int $folderId, Request $request): JsonResponse
    {
        try {
            $this->documentService->mergeDocuments(array_merge([FolderEnum::FOLDER_ID => $folderId], $request->toArray()));
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/{id}/update-workflow-status", name="update_workflow_status", methods="POST")
     */
    public function updateWorkflowStatus(int $id, Request $request): JsonResponse
    {
        try {
            $this->folderService->updateWorkflowStatus($id, $request->toArray());
        } catch (InternalApiResourceNotFound $exception) {
            throw new ApiException(Response::HTTP_NOT_FOUND, $exception->getMessage());
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
