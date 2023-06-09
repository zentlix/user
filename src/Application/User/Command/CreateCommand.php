<?php

declare(strict_types=1);

namespace Zentlix\User\Application\User\Command;

use Zentlix\Core\Application\Shared\Command\CreateCommandInterface;
use Zentlix\User\Domain\User\DataTransferObject\User;

final readonly class CreateCommand implements CreateCommandInterface
{
    public function __construct(
        public User $data = new User()
    ) {
    }
}
