<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\Event;

use Ramsey\Uuid\UuidInterface;

final class GroupWasDeleted
{
    public function __construct(
        public readonly UuidInterface $uuid,
        public readonly \DateTimeImmutable $deletedAt
    ) {
    }
}
