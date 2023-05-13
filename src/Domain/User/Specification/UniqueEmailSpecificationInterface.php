<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Specification;

use Zentlix\User\Domain\User\Exception\DuplicateEmailException;
use Zentlix\User\Domain\User\ValueObject\Email;

interface UniqueEmailSpecificationInterface
{
    /**
     * @throws DuplicateEmailException
     */
    public function isUnique(Email $email): bool;
}
