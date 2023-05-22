<?php

declare(strict_types=1);

namespace Zentlix\User\Application\User\Command\Auth;

use Spiral\AdminPanel\Security\AuthenticatorInterface;
use Spiral\AdminPanel\Security\Credentials;
use Spiral\Cqrs\Attribute\CommandHandler;

final class SignInHandler
{
    public function __construct(
        private readonly AuthenticatorInterface $authenticator
    ) {
    }

    #[CommandHandler]
    public function __invoke(SignInCommand $command): void
    {
        $this->authenticator->start(
            new Credentials($command->email->getValue(), $command->plainPassword),
            $command->sessionExpiration
        );
    }
}
