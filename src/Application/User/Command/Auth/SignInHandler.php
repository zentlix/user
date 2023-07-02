<?php

declare(strict_types=1);

namespace Zentlix\User\Application\User\Command\Auth;

use Spiral\AdminPanel\Security\AuthenticatorInterface;
use Spiral\AdminPanel\Security\Credentials;
use Spiral\Cqrs\Attribute\CommandHandler;

final readonly class SignInHandler
{
    public function __construct(
        private AuthenticatorInterface $authenticator
    ) {
    }

    #[CommandHandler]
    public function __invoke(SignInCommand $command): void
    {
        $this->authenticator->start(
            new Credentials($command->getEmail()->getValue(), $command->password),
            $command->getSessionExpiration()
        );
    }
}
