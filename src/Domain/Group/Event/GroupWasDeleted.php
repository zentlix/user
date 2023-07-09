<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\Event;

use Ramsey\Uuid\UuidInterface;
use Spiral\Marshaller\Meta\Marshal;

final class GroupWasDeleted
{
    public function __construct(
        public readonly UuidInterface $uuid,
        #[Marshal(name: 'deleted_at', of: \DateTimeImmutable::class)]
        public readonly \DateTimeImmutable $deletedAt
    ) {
    }
}
