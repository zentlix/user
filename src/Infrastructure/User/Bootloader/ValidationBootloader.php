<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\User\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Zentlix\User\Domain\User\Service\UserValidatorInterface;
use Zentlix\User\Infrastructure\User\Service\UserValidator;

final class ValidationBootloader extends Bootloader
{
    protected const BINDINGS = [
        UserValidatorInterface::class => UserValidator::class,
    ];
}
