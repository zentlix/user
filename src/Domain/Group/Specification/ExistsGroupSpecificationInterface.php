<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\Specification;

use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\Group\Exception\GroupNotFoundException;

interface ExistsGroupSpecificationInterface
{
    /**
     * @param UuidInterface|UuidInterface[] $uuid
     *
     * @throws GroupNotFoundException
     */
    public function isExists(array|UuidInterface $uuid): bool;
}
