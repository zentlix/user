<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\Event;

use Zentlix\User\Domain\Group\DataTransferObject\Group;

final class GroupWasCreated
{
    public function __construct(
        public readonly Group $group
    ) {
    }
}
