<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\User\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Zentlix\User\Domain\User\ReadModel\Repository\CheckUserByEmailInterface;
use Zentlix\User\Domain\User\ReadModel\Repository\CheckUserByPhoneInterface;
use Zentlix\User\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Zentlix\User\Infrastructure\User\ReadModel\Repository\CycleUserRepository;

final class ReadModelBootloader extends Bootloader
{
    protected const BINDINGS = [
        UserRepositoryInterface::class => CycleUserRepository::class,
        CheckUserByEmailInterface::class => CycleUserRepository::class,
        CheckUserByPhoneInterface::class => CycleUserRepository::class,
    ];
}
