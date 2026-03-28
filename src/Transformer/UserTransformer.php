<?php

declare(strict_types=1);

namespace App\Transformer;

use App\DTO\Response\UserResponse as UserResponseDto;
use App\Entity\User;

final readonly class UserTransformer
{
    public function toUserResponseDto(User $user): UserResponseDto
    {
        return new UserResponseDto(
            id: (string) $user->getId(),
            email: $user->getEmail(),
        );
    }
}
