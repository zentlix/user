<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Locale\Bootloader;

use Spiral\Boot\AbstractKernel;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Core\Container;
use Zentlix\Core\Infrastructure\Shared\Config\CoreConfig;
use Zentlix\Core\ReadEngines;
use Zentlix\User\Domain\Locale\ReadModel\Repository\CheckLocaleByCodeInterface;
use Zentlix\User\Domain\Locale\ReadModel\Repository\CheckLocaleInterface;
use Zentlix\User\Domain\Locale\ReadModel\Repository\LocaleRepositoryInterface;
use Zentlix\User\Infrastructure\Locale\ReadModel\Repository\CycleLocaleRepository;

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
            CheckLocaleInterface::class,
            $config->getReadModelBinding(CheckLocaleInterface::class, CycleLocaleRepository::class)
        );
        $container->bindSingleton(
            CheckLocaleByCodeInterface::class,
            $config->getReadModelBinding(CheckLocaleByCodeInterface::class,  CycleLocaleRepository::class)
        );
        $container->bindSingleton(
            LocaleRepositoryInterface::class,
            $config->getReadModelBinding(LocaleRepositoryInterface::class,  CycleLocaleRepository::class)
        );
    }

    private function bindElasticsearchRepositories(Container $container, CoreConfig $config): void
    {
        // TODO
    }
}
