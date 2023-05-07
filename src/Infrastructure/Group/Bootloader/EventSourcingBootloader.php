<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Zentlix\User\Domain\Group\Repository\GroupRepositoryInterface;
use Zentlix\User\Infrastructure\Group\Repository\GroupStore;

final class EventSourcingBootloader extends Bootloader
{
    protected const BINDINGS = [
        GroupRepositoryInterface::class => GroupStore::class
    ];
}
