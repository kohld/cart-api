<?php

declare(strict_types=1);

namespace App\Transformer;

use App\DTO\Response\CartItemResponse as CartItemResponseDto;
use App\DTO\Response\ProductResponse;
use App\Entity\CartItem;

final readonly class CartItemTransformer
{
    public function toCartItemResponseDto(CartItem $cartItem): CartItemResponseDto
    {
        $product = $cartItem->getProduct();

        return new CartItemResponseDto(
            id: (string) $cartItem->getId(),
            product: new ProductResponse(
                id: (string) $product->getId(),
                articleNumber: $product->getArticleNumber(),
                name: $product->getName(),
                price: $product->getPrice(),
            ),
            quantity: $cartItem->getQuantity(),
            price: $cartItem->getPrice(),
        );
    }
}
