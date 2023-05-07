<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Locale\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Zentlix\User\Domain\Locale\ReadModel\Repository\CheckLocaleByCodeInterface;
use Zentlix\User\Domain\Locale\ReadModel\Repository\CheckLocaleInterface;
use Zentlix\User\Domain\Locale\ReadModel\Repository\LocaleRepositoryInterface;
use Zentlix\User\Infrastructure\Locale\ReadModel\Repository\CycleLocaleRepository;

final class ReadModelBootloader extends Bootloader
{
    protected const BINDINGS = [
        CheckLocaleInterface::class => CycleLocaleRepository::class,
        CheckLocaleByCodeInterface::class => CycleLocaleRepository::class,
        LocaleRepositoryInterface::class => CycleLocaleRepository::class,
    ];
}
