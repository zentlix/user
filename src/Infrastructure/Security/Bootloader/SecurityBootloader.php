<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Security\Bootloader;

use Spiral\AdminPanel\Security\UserProviderInterface;
use Spiral\Boot\AbstractKernel;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Bootloader\Attributes\AttributesBootloader;
use Spiral\Bootloader\Auth\AuthBootloader;
use Spiral\Security\PermissionsInterface;
use Spiral\Security\Rule\AllowRule;
use Spiral\Security\Rule\ForbidRule;
use Spiral\Tokenizer\Bootloader\TokenizerListenerBootloader;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Zentlix\User\Domain\Group\DefaultGroups;
use Zentlix\User\Domain\Group\ReadModel\Repository\GroupRepositoryInterface;
use Zentlix\User\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Zentlix\User\Infrastructure\Security\PermissionsListener;
use Zentlix\User\Infrastructure\Security\PermissionsRegistry;
use Zentlix\User\Infrastructure\Shared\Config\UserConfig;

final class SecurityBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        AttributesBootloader::class,
        TokenizerListenerBootloader::class,
    ];

    protected const SINGLETONS = [
        PasswordHasherFactoryInterface::class => [self::class, 'initPasswordHasherFactory'],
        UserProviderInterface::class => UserRepositoryInterface::class,
        PermissionsRegistry::class => PermissionsRegistry::class,
    ];

    public function init(
        AuthBootloader $auth,
        TokenizerListenerBootloader $tokenizer,
        PermissionsListener $listener,
        AbstractKernel $kernel
    ): void {
        $auth->addActorProvider(UserRepositoryInterface::class);

        $tokenizer->addListener($listener);

        $kernel->booted(function (
            GroupRepositoryInterface $groupRepository,
            PermissionsInterface $permissions,
            PermissionsRegistry $registry
        ) {
            $this->registerRoles($groupRepository, $permissions, $registry);
        });
    }

    private function initPasswordHasherFactory(UserConfig $config): PasswordHasherFactoryInterface
    {
        return new PasswordHasherFactory($config->getPasswordHashers());
    }

    private function registerRoles(
        GroupRepositoryInterface $groupRepository,
        PermissionsInterface $permissions,
        PermissionsRegistry $registry
    ): void {
        if (!$groupRepository->isAvailable()) {
            return;
        }

        foreach ($groupRepository->findAll() as $group) {
            $rule = $group->code === DefaultGroups::Administrators->value ? AllowRule::class : ForbidRule::class;

            if (!$permissions->hasRole($group->code)) {
                $permissions->addRole($group->code);
            }

            foreach ($registry->getPermissions() as $permission) {
                if (isset($group->permissions[$permission])) {
                    $rule = $group->permissions[$permission];
                }
                $permissions->associate($group->code, $permission, $rule);
            }
        }
    }
}
