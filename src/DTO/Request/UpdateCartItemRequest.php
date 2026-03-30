<?php

declare(strict_types=1);

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

final class UpdateCartItemRequest
{
    #[NotBlank]
    #[Positive]
    public int $quantity;

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
