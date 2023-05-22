<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Shared\Bootloader;

use Spiral\AdminPanel\Security\UserProviderInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Bootloader\Auth\AuthBootloader;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Zentlix\User\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Zentlix\User\Infrastructure\Shared\Config\UserConfig;

final class SecurityBootloader extends Bootloader
{
    protected const SINGLETONS = [
        PasswordHasherFactoryInterface::class => [self::class, 'initPasswordHasherFactory'],
        UserProviderInterface::class => UserRepositoryInterface::class,
    ];

    private function initPasswordHasherFactory(UserConfig $config): PasswordHasherFactoryInterface
    {
        return new PasswordHasherFactory($config->getPasswordHashers());
    }

    public function init(AuthBootloader $auth): void
    {
        $auth->addActorProvider(UserRepositoryInterface::class);
    }
}
