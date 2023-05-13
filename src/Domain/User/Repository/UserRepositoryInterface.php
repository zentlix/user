<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Repository;

use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\User\User;

interface UserRepositoryInterface
{
    public function get(UuidInterface $uuid): User;

    public function store(User $user): void;
}
