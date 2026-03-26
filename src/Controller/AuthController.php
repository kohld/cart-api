<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Request\RegisterRequest;
use App\Service\UserService;
use App\Transformer\UserTransformer;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth')]
final class AuthController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly UserTransformer $userTransformer,
    ) {
    }

    #[Route('/register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload] RegisterRequest $registerRequest,
    ): JsonResponse {
        $user = $this->userService->register($registerRequest);

        $response = $this->json(
            $this->userTransformer->toUserResponseDto($user),
            Response::HTTP_CREATED,
        );

        $response->headers->set('Cache-Control', 'no-store');

        return $response;
    }

    /**
     * @throws LogicException
     */
    #[Route('/login', methods: ['POST'])]
    public function login(): never
    {
        // Intercepted by LexikJWT bundle: this code is never reached
        throw new LogicException('This method should not be called directly.');
    }
}
