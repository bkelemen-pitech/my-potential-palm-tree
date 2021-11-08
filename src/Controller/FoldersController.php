<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\BepremsEnum;
use App\Exception\ApiException;
use App\Fetcher\FolderFetcher;
use App\Services\FolderService;
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
    protected FolderService $folderService;

    public function __construct(FolderService $folderService)
    {
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
     * @Route("/", name="get_folder", methods="GET")
     */
    public function getFolder(Request $request): JsonResponse
    {
        $folders = $this->folderService->getFolders($request->query->all());

        return $this->json($folders);
    }

    /**
     * @Route("/{id}", name="get_by_id", methods="GET")
     */
    public function getFolderById(int $id, FolderFetcher $folderFetcher): JsonResponse
    {
        try {
            $filters = [
                BepremsEnum::PERSON_ORDER => 'ASC NULLS FIRST',
                BepremsEnum::PERSON_INFO_ORDER => 'ASC',
                BepremsEnum::PERSON_ORDER_BY => 'prenom,nom',
                BepremsEnum::PERSON_INFO_ORDER_BY => 'source,creation'
            ];

            $folderData = $folderFetcher->getFolderData($id, $filters);
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_NOT_FOUND, $exception->getMessage());
        }

        return $this->json($folderData);
    }
}
