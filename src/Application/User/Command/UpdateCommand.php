<?php

declare(strict_types=1);

namespace Zentlix\User\Application\User\Command;

use Zentlix\Core\Application\Shared\Command\UpdateCommandInterface;
use Zentlix\User\Domain\User\DataTransferObject\User;

final readonly class UpdateCommand implements UpdateCommandInterface
{
    public function __construct(
        public User $user
    ) {
    }
}
