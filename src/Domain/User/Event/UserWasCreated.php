<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Event;

use Zentlix\User\Domain\User\DataTransferObject\User;

final class UserWasCreated
{
    public function __construct(
        public readonly User $data
    ) {
    }
}
