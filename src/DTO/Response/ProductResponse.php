<?php

declare(strict_types=1);

namespace App\DTO\Response;

final readonly class ProductResponse
{
    public function __construct(
        public string $id,
        public string $articleNumber,
        public string $name,
        public string $price,
    ) {
    }
}
