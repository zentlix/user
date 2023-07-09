<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\User\Bootloader;

use Spiral\Boot\AbstractKernel;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Core\Container;
use Zentlix\Core\Infrastructure\Shared\Config\CoreConfig;
use Zentlix\Core\ReadEngines;
use Zentlix\User\Domain\User\ReadModel\Repository\CheckUserByEmailInterface;
use Zentlix\User\Domain\User\ReadModel\Repository\CheckUserByPhoneInterface;
use Zentlix\User\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Zentlix\User\Infrastructure\User\ReadModel\Repository\CycleUserRepository;

final class ReadModelBootloader extends Bootloader
{
    public function init(AbstractKernel $kernel): void
    {
        $kernel->booting(function (Container $container, CoreConfig $config) {
            switch ($config->getReadEngine()) {
                case ReadEngines::Cycle:
                    $this->bindCycleRepositories($container, $config);
                    break;
                case ReadEngines::Elasticsearch:
                    $this->bindElasticsearchRepositories($container, $config);
                    break;
            }
        });
    }

    private function bindCycleRepositories(Container $container, CoreConfig $config): void
    {
        $container->bindSingleton(
            UserRepositoryInterface::class,
            $config->getReadModelBinding(UserRepositoryInterface::class, CycleUserRepository::class)
        );
        $container->bindSingleton(
            CheckUserByEmailInterface::class,
            $config->getReadModelBinding(CheckUserByEmailInterface::class, CycleUserRepository::class)
        );
        $container->bindSingleton(
            CheckUserByPhoneInterface::class,
            $config->getReadModelBinding(CheckUserByPhoneInterface::class, CycleUserRepository::class)
        );
    }

    private function bindElasticsearchRepositories(Container $container, CoreConfig $config): void
    {
        // TODO
    }
}
