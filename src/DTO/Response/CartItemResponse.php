<?php

declare(strict_types=1);

namespace App\DTO\Response;

final readonly class CartItemResponse
{
    public function __construct(
        public string $id,
        public ProductResponse $product,
        public int $quantity,
        public string $price,
    ) {
    }
}
