<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Shared\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Config\ConfiguratorInterface;
use Zentlix\User\Domain\User\User;
use Zentlix\User\Infrastructure\Group\Bootloader as GroupBootloaders;
use Zentlix\User\Infrastructure\Locale\Bootloader as LocaleBootloaders;
use Zentlix\User\Infrastructure\Shared\Bootloader as SharedBootloaders;
use Zentlix\User\Infrastructure\Security\Bootloader as SecurityBootloaders;
use Zentlix\User\Infrastructure\User\Bootloader as UserBootloaders;
use Zentlix\User\Infrastructure\Shared\Config\UserConfig;

final class UserBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        GroupBootloaders\EventSourcingBootloader::class,
        GroupBootloaders\ReadModelBootloader::class,
        GroupBootloaders\SpecificationBootloader::class,
        GroupBootloaders\ValidationBootloader::class,
        LocaleBootloaders\EventSourcingBootloader::class,
        LocaleBootloaders\ReadModelBootloader::class,
        LocaleBootloaders\SpecificationBootloader::class,
        LocaleBootloaders\ValidationBootloader::class,
        SharedBootloaders\I18nBootloader::class,
        SecurityBootloaders\SecurityBootloader::class,
        UserBootloaders\EventSourcingBootloader::class,
        UserBootloaders\ReadModelBootloader::class,
        UserBootloaders\SpecificationBootloader::class,
        UserBootloaders\ValidationBootloader::class,
    ];

    public function __construct(
        private readonly ConfiguratorInterface $config
    ) {
    }

    public function init(): void
    {
        $this->initConfig();
    }

    private function initConfig(): void
    {
        $this->config->setDefaults(
            UserConfig::CONFIG,
            [
                'password_hashers' => [
                    User::class => ['algorithm' => 'auto'],
                ]
            ]
        );
    }
}
