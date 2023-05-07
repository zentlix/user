<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Zentlix\User\Domain\Group\ReadModel\Repository\CheckGroupByCodeInterface;
use Zentlix\User\Infrastructure\Group\ReadModel\Repository\CycleGroupRepository;

final class ReadModelBootloader extends Bootloader
{
    protected const BINDINGS = [
        CheckGroupByCodeInterface::class => CycleGroupRepository::class,
    ];
}
