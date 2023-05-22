<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Shared\Security;

use Spiral\AdminPanel\Security\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Zentlix\User\Domain\User\User;

final class PasswordHasher implements PasswordHasherInterface
{
    public function __construct(
        private readonly PasswordHasherFactoryInterface $passwordHasherFactory
    ) {
    }

    public function hashPassword(string $plainPassword): string
    {
        return $this->passwordHasherFactory->getPasswordHasher(User::class)->hash($plainPassword);
    }

    public function isPasswordValid(string $plainPassword, string $password): bool
    {
        return $this->passwordHasherFactory->getPasswordHasher(User::class)->verify($password, $plainPassword);
    }
}
