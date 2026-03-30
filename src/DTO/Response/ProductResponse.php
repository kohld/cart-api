<?php

declare(strict_types=1);

namespace App\DTO\Response;

final readonly class ProductResponse extends BaseResponse
{
    /**
     * @param array<string, array{href: string, method: string}> $_links
     */
    public function __construct(
        public string $id,
        public string $articleNumber,
        public string $name,
        public string $price,
        array $_links = [],
    ) {
        parent::__construct($_links);
    }
}
