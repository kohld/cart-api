<?php

declare(strict_types=1);

namespace App\Transformer;

use App\DTO\Response\CartItemResponse as CartItemResponseDto;
use App\Entity\CartItem;

final class CartItemTransformer
{
    public function toCartItemResponseDto(CartItem $cartItem): CartItemResponseDto
    {
        // Map CartItem to CartItemResponse DTO
        // id, product (id, name, articleNumber), quantity, price
    }
}
