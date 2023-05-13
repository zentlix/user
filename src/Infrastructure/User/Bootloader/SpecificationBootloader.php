<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\User\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Zentlix\User\Domain\User\Specification\UniqueEmailSpecificationInterface;
use Zentlix\User\Domain\User\Specification\UniquePhoneSpecificationInterface;
use Zentlix\User\Infrastructure\User\Specification\UniqueEmailSpecification;
use Zentlix\User\Infrastructure\User\Specification\UniquePhoneSpecification;

final class SpecificationBootloader extends Bootloader
{
    protected const BINDINGS = [
        UniqueEmailSpecificationInterface::class => UniqueEmailSpecification::class,
        UniquePhoneSpecificationInterface::class => UniquePhoneSpecification::class,
    ];
}
