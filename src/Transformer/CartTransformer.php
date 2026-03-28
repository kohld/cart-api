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
        $items = array_map(
            fn ($item) => $this->cartItemTransformer->toCartItemResponseDto($item),
            $cart->getItems()->toArray(),
        );

        $total = '0.00';
        foreach ($cart->getItems() as $item) {
            $total = bcadd($total, bcmul($item->getPrice(), (string) $item->getQuantity(), 2), 2);
        }

        return new CartResponseDto(
            id: (string) $cart->getId(),
            items: $items,
            total: $total,
        );
    }
}
