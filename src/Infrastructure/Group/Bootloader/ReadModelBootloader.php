<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\Bootloader;

use Spiral\Boot\AbstractKernel;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Core\Container;
use Zentlix\Core\Infrastructure\Shared\Config\CoreConfig;
use Zentlix\Core\ReadEngines;
use Zentlix\User\Domain\Group\ReadModel\Repository\CheckGroupByCodeInterface;
use Zentlix\User\Domain\Group\ReadModel\Repository\CheckGroupInterface;
use Zentlix\User\Domain\Group\ReadModel\Repository\GroupRepositoryInterface;
use Zentlix\User\Infrastructure\Group\ReadModel\Repository\CycleGroupRepository;

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
            GroupRepositoryInterface::class,
            $config->getReadModelBinding(GroupRepositoryInterface::class, CycleGroupRepository::class)
        );
        $container->bindSingleton(
            CheckGroupInterface::class,
            $config->getReadModelBinding(CheckGroupInterface::class, CycleGroupRepository::class)
        );
        $container->bindSingleton(
            CheckGroupByCodeInterface::class,
            $config->getReadModelBinding(CheckGroupByCodeInterface::class, CycleGroupRepository::class)
        );
    }

    private function bindElasticsearchRepositories(Container $container, CoreConfig $config): void
    {
        // TODO
    }
}
