<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Locale\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Zentlix\User\Domain\Locale\Service\LocaleValidatorInterface;
use Zentlix\User\Infrastructure\Locale\Service\LocaleValidator;

final class ValidationBootloader extends Bootloader
{
    protected const BINDINGS = [
        LocaleValidatorInterface::class => LocaleValidator::class,
    ];
}
