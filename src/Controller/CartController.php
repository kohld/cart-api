<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CartService;
use App\Transformer\CartTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/carts')]
final class CartController extends AbstractController
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CartTransformer $cartTransformer,
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function show(): JsonResponse
    {
        // Get authenticated user via $this->getUser()
        // Load cart via CartService::getCart(User)
        // Transform to CartResponse via CartTransformer::toCartResponseDto()
        // Return 200 with CartResponse
        // Set Cache-Control: no-store
    }

    #[Route('/items', methods: ['POST'])]
    public function addItem(): JsonResponse
    {
        // Validate request via #[MapRequestPayload] AddCartItemRequest
        // Get authenticated user via $this->getUser()
        // Load product by productId via CartService
        // If product not found: return 404
        // If item already in cart: add quantity to existing item
        // Otherwise: add new CartItem with price snapshot from Product
        // Return 201 with updated CartResponse
        // Set Cache-Control: no-store
    }

    #[Route('/items/{id}', methods: ['PATCH'])]
    public function updateItem(string $id): JsonResponse
    {
        // Validate request via #[MapRequestPayload] UpdateCartItemRequest
        // Get authenticated user via $this->getUser()
        // Load CartItem by id via CartService
        // If not found or does not belong to user cart: return 404
        // Update quantity
        // Return 200 with updated CartResponse
        // Set Cache-Control: no-store
    }

    #[Route('/items/{id}', methods: ['DELETE'])]
    public function removeItem(string $id): JsonResponse
    {
        // Get authenticated user via $this->getUser()
        // Load CartItem by id via CartService
        // If not found or does not belong to user cart: return 404
        // Remove CartItem
        // Return 204 No Content
        // Set Cache-Control: no-store
    }
}
