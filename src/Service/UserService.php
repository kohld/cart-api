<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\RegisterRequest as RegisterRequestDto;
use App\Entity\Cart;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    /**
     * Registers a new user with their own and associated shopping cart (1:1 relationship).
     *
     * Creates a new user entity, hashes their password, creates an associated cart,
     * and persists everything to the database.
     *
     * @param RegisterRequestDto $request The registration request containing user data
     *
     * @return User The newly created user entity with an associated cart
     */
    public function register(RegisterRequestDto $request): User
    {
        $user = new User();
        $user->setEmail($request->email);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $request->plainPassword)
        );

        $cart = new Cart($user);
        $user->setCart($cart);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
