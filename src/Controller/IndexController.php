<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    /**
     * API index endpoint.
     *
     * Returns basic information about the cart API including name, version, and health endpoint.
     *
     * @return JsonResponse JSON response containing API metadata
     */
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'name' => 'cart-api',
            'version' => '1.0.0',
            'health' => '/api/v1/health',
        ]);
    }
}
