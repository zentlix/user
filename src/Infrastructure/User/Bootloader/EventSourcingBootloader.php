<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\User\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Zentlix\User\Domain\User\Repository\UserRepositoryInterface;
use Zentlix\User\Infrastructure\User\Repository\UserStore;

final class EventSourcingBootloader extends Bootloader
{
    protected const BINDINGS = [
        UserRepositoryInterface::class => UserStore::class
    ];
}
