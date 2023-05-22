<?php

declare(strict_types=1);

namespace Zentlix\User\Application\User\Command\Auth;

use Zentlix\Core\Application\Shared\Command\CommandInterface;
use Zentlix\User\Domain\User\ValueObject\Email;

final class SignInCommand implements CommandInterface
{
    /**
     * @param non-empty-string $plainPassword
     */
    public function __construct(
        public readonly Email $email,
        public readonly string $plainPassword,
        public readonly \DateTimeInterface $sessionExpiration
    ) {
    }
}
