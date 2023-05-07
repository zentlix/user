<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\Specification;

use Zentlix\User\Domain\Group\Exception\DuplicateCodeException;

interface UniqueCodeSpecificationInterface
{
    /**
     * @psalm-param non-empty-string $code
     *
     * @throws DuplicateCodeException
     */
    public function isUnique(string $code): bool;
}
