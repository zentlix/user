<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Specification;

use libphonenumber\PhoneNumber;
use Zentlix\User\Domain\User\Exception\DuplicatePhoneException;

interface UniquePhoneSpecificationInterface
{
    /**
     * @throws DuplicatePhoneException
     */
    public function isUnique(PhoneNumber $phone): bool;
}
