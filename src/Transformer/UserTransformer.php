<?php

declare(strict_types=1);

namespace App\Transformer;

use App\DTO\Response\UserResponse as UserResponseDto;
use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class UserTransformer
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function toUserResponseDto(User $user): UserResponseDto
    {
        return new UserResponseDto(
            id: (string) $user->getId(),
            email: $user->getEmail(),
            _links: [
                'login' => [
                    'href' => $this->urlGenerator->generate('app_auth_login'),
                    'method' => 'POST',
                ],
                'cart' => [
                    'href' => $this->urlGenerator->generate('app_cart_show'),
                    'method' => 'GET',
                ],
            ],
        );
    }
}
