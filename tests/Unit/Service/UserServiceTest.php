<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\DTO\Request\RegisterRequest;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserServiceTest extends TestCase
{
    private EntityManagerInterface&MockObject $entityManager;
    private UserPasswordHasherInterface&MockObject $passwordHasher;
    private UserService $userService;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $this->userService = new UserService(
            $this->entityManager,
            $this->passwordHasher,
        );
    }

    public function testRegisterCreatesUserWithHashedPassword(): void
    {
        $request = new RegisterRequest();
        $request->email = 'test@example.com';
        $request->plainPassword = 'password123';

        $this->passwordHasher
            ->expects($this->once())
            ->method('hashPassword')
            ->willReturn('hashed_password');

        $this->entityManager
            ->expects($this->once())
            ->method('persist');

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $user = $this->userService->register($request);

        $this->assertSame('test@example.com', $user->getEmail());
        $this->assertSame('hashed_password', $user->getPassword());
        $this->assertNotNull($user->getCart());
    }
}
