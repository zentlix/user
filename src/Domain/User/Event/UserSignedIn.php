<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Event;

use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\User\ValueObject\Email;

final class UserSignedIn
{
    public function __construct(
        public readonly UuidInterface $uuid,
        public readonly Email $email,
        public \DateTimeImmutable $signedInAt
    ) {
    }
}
