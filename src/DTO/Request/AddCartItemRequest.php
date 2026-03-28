<?php

declare(strict_types=1);

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Uuid;

final class AddCartItemRequest
{
    #[NotBlank]
    #[Uuid]
    public string $productId;

    #[NotBlank]
    #[Positive]
    public int $quantity;
}
