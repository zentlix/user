<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Zentlix\User\Domain\Group\Service\GroupValidatorInterface;
use Zentlix\User\Infrastructure\Group\Service\GroupValidator;

final class ValidationBootloader extends Bootloader
{
    protected const BINDINGS = [
        GroupValidatorInterface::class => GroupValidator::class,
    ];
}
