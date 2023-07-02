<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Event;

use Zentlix\User\Domain\User\DataTransferObject\User;

final readonly class UserWasUpdated
{
    public function __construct(
        public User $data
    ) {
    }
}
