<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\Repository;

use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\Group\Group;

interface GroupRepositoryInterface
{
    public function get(UuidInterface $uuid): Group;

    public function store(Group $group): void;
}
