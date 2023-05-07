<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Locale\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Zentlix\User\Domain\Locale\Repository\LocaleRepositoryInterface;
use Zentlix\User\Infrastructure\Locale\Repository\LocaleStore;

final class EventSourcingBootloader extends Bootloader
{
    protected const BINDINGS = [
        LocaleRepositoryInterface::class => LocaleStore::class
    ];
}
