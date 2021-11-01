<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\FolderService;
use App\Traits\UserDataTrait;
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
    use UserDataTrait;

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
//        $userData = $this->getUserData();
        $folders = $this->folderService->getFolders($request->query->all());

        return $this->json($folders);
    }
}
