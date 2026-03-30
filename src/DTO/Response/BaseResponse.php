<?php

declare(strict_types=1);

namespace App\DTO\Response;

abstract readonly class BaseResponse
{
    /**
     * @param array<string, array{href: string, method: string}> $_links
     */
    public function __construct(public array $_links = [])
    {
    }
}
