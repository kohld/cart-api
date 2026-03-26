<?php

declare(strict_types=1);

namespace App\Transformer;

use App\DTO\Response\CartResponse as CartResponseDto;
use App\Entity\Cart;

final class CartTransformer
{
    public function __construct(
        private readonly CartItemTransformer $cartItemTransformer,
    ) {
    }

    public function toCartResponseDto(Cart $cart): CartResponseDto
    {
        // Map Cart to CartResponse DTO
        // id, items (via CartItemTransformer), total (sum of item prices * quantities)
    }
}
