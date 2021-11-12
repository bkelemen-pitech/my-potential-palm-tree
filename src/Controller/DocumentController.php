<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ApiException;
use App\Exception\ResourceNotFoundException;
use App\Services\DocumentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            $includeFiles = boolval($request->query->get('include-files'));
            $document = $documentService->getDocumentByUid($documentUid, $includeFiles);
        } catch (ResourceNotFoundException $exception) {
            throw new ApiException(Response::HTTP_NOT_FOUND, $exception->getMessage());
        }

        return $this->json($document);
    }
}
