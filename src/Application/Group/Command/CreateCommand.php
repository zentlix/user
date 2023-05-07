<?php

declare(strict_types=1);

namespace Zentlix\User\Application\Group\Command;

use Zentlix\Core\Application\Shared\Command\CreateCommandInterface;
use Zentlix\User\Domain\Group\DataTransferObject\Group;

final class CreateCommand implements CreateCommandInterface
{
    public function __construct(
        public readonly Group $data = new Group()
    ) {
    }
}
