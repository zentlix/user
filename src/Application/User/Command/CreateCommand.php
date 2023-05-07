<?php

declare(strict_types=1);

namespace Zentlix\User\Application\User\Command;

use Zentlix\Core\Application\Shared\Command\CreateCommandInterface;
use Zentlix\User\Domain\User\DataTransferObject\User;

final class CreateCommand implements CreateCommandInterface
{
    public function __construct(
        public readonly User $data = new User()
    ) {
    }
}
