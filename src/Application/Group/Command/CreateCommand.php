<?php

declare(strict_types=1);

namespace Zentlix\User\Application\Group\Command;

use Zentlix\Core\Application\Shared\Command\CreateCommandInterface;
use Zentlix\User\Domain\Group\DataTransferObject\Group;

final readonly class CreateCommand implements CreateCommandInterface
{
    public function __construct(
        public Group $data = new Group()
    ) {
    }
}
