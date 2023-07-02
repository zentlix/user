<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Event;

use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\User\ValueObject\Email;

final readonly class UserSignedIn
{
    public function __construct(
        public UuidInterface $uuid,
        public Email $email,
        public \DateTimeImmutable $signedInAt
    ) {
    }
}
