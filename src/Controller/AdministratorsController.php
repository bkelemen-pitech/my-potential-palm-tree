<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ApiException;
use App\Service\AdministratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/administrators", name="api_v1_administrators_")
 */
class AdministratorsController extends AbstractController
{
    protected AdministratorService $administratorService;

    public function __construct(
        AdministratorService $administratorService
    ) {
        $this->administratorService = $administratorService;
    }

    /**
     * @Route("/", name="get_administrators", methods="GET")
     */
    public function getAdministrators(Request $request): JsonResponse
    {
        try {
            $administrators = $this->administratorService->getAdministrators($request->query->all());
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }

        return $this->json($administrators);
    }
}
