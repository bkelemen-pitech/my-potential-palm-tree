<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/folders", name="api_v1_folders_")
 */
class FoldersController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function test(): Response
    {

        return $this->json(['test' => 'test']);
    }
}
