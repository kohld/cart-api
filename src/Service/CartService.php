<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\AddCartItemRequest;
use App\DTO\Request\UpdateCartItemRequest;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\User;

final class CartService
{
    public function getCart(User $user): Cart
    {
        // Return the cart associated with the user
    }

    public function addItem(Cart $cart, AddCartItemRequest $request): Cart
    {
        // Load Product by request->productId
        // If product not found: throw NotFoundException
        // Check if CartItem with same product already exists in cart
        // If yes: increase quantity by 1 and persist
        // If no: create new CartItem with price snapshot from Product, persist
        // Return updated Cart
    }

    public function updateItem(Cart $cart, string $itemId, UpdateCartItemRequest $request): CartItem
    {
        // Find CartItem by id within the cart
        // If not found: throw NotFoundException
        // Update quantity
        // Persist and return updated CartItem
    }

    public function removeItem(Cart $cart, string $itemId): void
    {
        // Find CartItem by id within the cart
        // If not found: throw NotFoundException
        // Remove CartItem and flush
    }
}
