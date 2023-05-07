<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Locale\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Zentlix\User\Domain\Locale\ReadModel\Repository\CheckLocaleByCodeInterface;
use Zentlix\User\Infrastructure\Locale\ReadModel\Repository\CycleLocaleRepository;

final class ReadModelBootloader extends Bootloader
{
    protected const BINDINGS = [
        CheckLocaleByCodeInterface::class => CycleLocaleRepository::class,
    ];
}
