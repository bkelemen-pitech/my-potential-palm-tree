<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\DocumentEnum;
use App\Exception\ApiException;
use App\Service\DocumentService;
use Kyc\InternalApiBundle\Exception\ResourceNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/documents", name="api_v1_documents_")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/{documentUid}", name="get_document_details", methods="GET")
     */
    public function getDocument(string $documentUid, Request $request, DocumentService $documentService): JsonResponse
    {
        try {
            $includeFiles = filter_var($request->query->get(DocumentEnum::INCLUDE_FILES_PARAM), FILTER_VALIDATE_BOOLEAN);
            $document = $documentService->getDocumentByUid($documentUid, $includeFiles);
        } catch (ResourceNotFoundException $exception) {
            throw new ApiException(Response::HTTP_NOT_FOUND, $exception->getMessage());
        }

        return $this->json($document);
    }

    /**
     * @Route("/treat", name="treat_document", methods="POST")
     */
    public function treatDocument(Request $request, DocumentService $documentService)
    {
        try {
            $documentService->treatDocument($request->toArray());

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }
    }

    /**
     * @Route("/fields", name="document_fields", methods="GET", priority=2)
     */
    public function getDocumentFields(Request $request, DocumentService $documentService): JsonResponse
    {
        try {
            $documentFields = $documentService->getDocumentFields($request->query->all());

            return $this->json($documentFields);
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }
    }

    /**
     * @Route("/document-data-logs", name="document_data_logs", methods="GET", priority=2)
     */
    public function getDocumentDataLogs(Request $request, DocumentService $documentService): JsonResponse
    {
        try {
            $documentDataLogs = $documentService->getDocumentDataLogs($request->query->all());

            return $this->json([DocumentEnum::DOCUMENT_DATA_LOGS => $documentDataLogs]);
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }
    }

    /**
     * @Route("/{document_uid}", name="delete_document", methods="DELETE")
     */
    public function deleteDocument(string $document_uid, DocumentService $documentService)
    {
        try {
            $documentService->deleteDocument([DocumentEnum::DOCUMENT_UID => $document_uid]);

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (ResourceNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }
    }
}
