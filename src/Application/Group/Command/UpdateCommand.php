<?php

declare(strict_types=1);

namespace Zentlix\User\Application\Group\Command;

use Zentlix\Core\Application\Shared\Command\UpdateCommandInterface;
use Zentlix\User\Domain\Group\DataTransferObject\Group;

final readonly class UpdateCommand implements UpdateCommandInterface
{
    public Group $data;

    public function __construct(Group $group)
    {
        $this->data = $group;
    }
}
