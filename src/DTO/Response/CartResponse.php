<?php

declare(strict_types=1);

namespace App\DTO\Response;

final readonly class CartResponse extends BaseResponse
{
    /**
     * @param CartItemResponse[]                                 $items
     * @param array<string, array{href: string, method: string}> $_links
     */
    public function __construct(
        public string $id,
        public array $items,
        public string $total,
        array $_links = [],
    ) {
        parent::__construct($_links);
    }
}
