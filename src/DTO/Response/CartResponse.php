<?php

declare(strict_types=1);

namespace App\DTO\Response;

final readonly class CartResponse
{
    /**
     * @param CartItemResponse[] $items
     */
    public function __construct(
        public string $id,
        public array $items,
        public string $total,
    ) {
    }
}
