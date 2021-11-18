<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\BepremsEnum;
use App\Enum\PersonEnum;
use App\Exception\ApiException;
use App\Facade\InternalApi\PersonFacade;
use App\Fetcher\FolderFetcher;
use App\Services\FolderService;
use App\Services\PersonService;
use App\Strategy\Person\CreatePerson;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/folders", name="api_v1_folders_")
 */
class FoldersController extends AbstractController
{
    protected PersonService $personService;
    protected FolderService $folderService;

    public function __construct(
        PersonService $personService,
        FolderService $folderService
    )
    {
        $this->personService = $personService;
        $this->folderService = $folderService;
    }

    /**
     * @Route("/test", name="test")
     */
    public function test(): Response
    {
        return $this->json(['test' => 'test']);
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
    public function getFolderById(int $id, FolderFetcher $folderFetcher): JsonResponse
    {
        try {
            $folderData = $folderFetcher->getFolderData($id, BepremsEnum::DEFAULT_FOLDER_BY_ID_FILTERS);
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
    public function createPerson(int $id, Request $request): JsonResponse {
        try {
            $personUid = $this->personService->addPerson($id, $request->toArray());
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }

        return $this->json([PersonEnum::PERSON_UID => $personUid]);
    }
}
