<?php

declare(strict_types=1);

namespace Zentlix\User\Application\Group\Command;

use Zentlix\Core\Application\Shared\Command\DeleteCommandInterface;
use Zentlix\User\Domain\Group\ReadModel\GroupView;

final readonly class DeleteCommand implements DeleteCommandInterface
{
    public function __construct(
        public GroupView $group
    ) {
    }
}
