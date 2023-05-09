<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\ReadModel\Repository;

use Ramsey\Uuid\UuidInterface;

interface CheckGroupInterface
{
    /**
     * @param UuidInterface|UuidInterface[] $uuid
     *
     * @psalm-return ($uuid is array ? array : ?UuidInterface)
     */
    public function exists(array|UuidInterface $uuid): UuidInterface|array|null;
}
