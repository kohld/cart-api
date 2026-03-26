<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\RegisterRequest as RegisterRequestDto;
use App\Entity\User;

final class UserService
{
    public function register(RegisterRequestDto $registerRequestDto): User
    {
        return new User();
    }
}
