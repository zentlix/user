<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Zentlix\User\Domain\Group\Specification\ExistsGroupSpecificationInterface;
use Zentlix\User\Domain\Group\Specification\UniqueCodeSpecificationInterface;
use Zentlix\User\Infrastructure\Group\Specification\ExistsGroupSpecification;
use Zentlix\User\Infrastructure\Group\Specification\UniqueCodeSpecification;

final class SpecificationBootloader extends Bootloader
{
    protected const BINDINGS = [
        UniqueCodeSpecificationInterface::class => UniqueCodeSpecification::class,
        ExistsGroupSpecificationInterface::class => ExistsGroupSpecification::class,
    ];
}
