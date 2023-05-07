<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Locale\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Zentlix\User\Domain\Locale\Specification\ExistsLocaleSpecificationInterface;
use Zentlix\User\Domain\Locale\Specification\UniqueCodeSpecificationInterface;
use Zentlix\User\Infrastructure\Locale\Specification\ExistsLocaleSpecification;
use Zentlix\User\Infrastructure\Locale\Specification\UniqueCodeSpecification;

final class SpecificationBootloader extends Bootloader
{
    protected const BINDINGS = [
        ExistsLocaleSpecificationInterface::class => ExistsLocaleSpecification::class,
        UniqueCodeSpecificationInterface::class => UniqueCodeSpecification::class,
    ];
}
