<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Request\AddCartItemRequest as AddCartItemRequestDto;
use App\DTO\Request\UpdateCartItemRequest as UpdateCartItemRequestDto;
use App\Entity\User;
use App\Service\CartService;
use App\Transformer\CartTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/carts')]
final class CartController extends AbstractController
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CartTransformer $cartTransformer,
    ) {
    }

    /**
     * Get the cart for the authenticated user.
     *
     * @return JsonResponse JSON response containing the cart data
     */
    #[Route('/me', methods: ['GET'])]
    public function show(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $cart = $this->cartService->getCart($user);

        return $this->json(
            $this->cartTransformer->toCartResponseDto($cart),
            Response::HTTP_OK,
            ['Cache-Control' => 'no-store'],
        );
    }

    /**
     * Add an item to the current authenticated user's cart.
     *
     * @param AddCartItemRequestDto $request The request containing product and quantity data
     *
     * @return JsonResponse JSON response containing the updated cart data
     */
    #[Route('/me/items', methods: ['POST'])]
    public function addItem(#[MapRequestPayload] AddCartItemRequestDto $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $cart = $this->cartService->addItem($this->cartService->getCart($user), $request);

        return $this->json(
            $this->cartTransformer->toCartResponseDto($cart),
            Response::HTTP_CREATED,
            ['Cache-Control' => 'no-store'],
        );
    }

    /**
     * Update an items quantity in the authenticated user's cart.
     *
     * @param string                   $id      The ID of the cart item to update
     * @param UpdateCartItemRequestDto $request The request containing updated quantity data
     *
     * @return JsonResponse JSON response containing the updated cart data
     */
    #[Route('/me/items/{id}', methods: ['PATCH'])]
    public function updateItem(string $id, #[MapRequestPayload] UpdateCartItemRequestDto $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $cart = $this->cartService->updateItem($this->cartService->getCart($user), $id, $request);

        return $this->json(
            $this->cartTransformer->toCartResponseDto($cart),
            Response::HTTP_OK,
            ['Cache-Control' => 'no-store'],
        );
    }

    /**
     * Remove an item from the authenticated user's cart.
     *
     * @param string $id The ID of the cart item to remove
     *
     * @return JsonResponse Empty response with HTTP 204 status
     */
    #[Route('/me/items/{id}', methods: ['DELETE'])]
    public function removeItem(string $id): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->cartService->removeItem($this->cartService->getCart($user), $id);

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT,
            ['Cache-Control' => 'no-store']
        );
    }
}
