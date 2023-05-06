<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Locale\Specification;

use Zentlix\User\Domain\Locale\Exception\DuplicateCodeException;

interface UniqueCodeSpecificationInterface
{
    /**
     * @param non-empty-string $code
     *
     * @throws DuplicateCodeException
     */
    public function isUnique(string $code): bool;
}
