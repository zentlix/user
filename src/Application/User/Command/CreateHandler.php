<?php

declare(strict_types=1);

namespace Zentlix\User\Application\User\Command;

use Spiral\Cqrs\Attribute\CommandHandler;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Zentlix\User\Domain\User\Repository\UserRepositoryInterface;
use Zentlix\User\Domain\User\Service\UserValidatorInterface;
use Zentlix\User\Domain\User\User;

final readonly class CreateHandler
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private UserValidatorInterface $validator,
        private PasswordHasherFactoryInterface $passwordHasherFactory
    ) {
    }

    #[CommandHandler]
    public function __invoke(CreateCommand $command): void
    {
        /** @var non-empty-string $hash */
        $hash = $this->passwordHasherFactory->getPasswordHasher(User::class)->hash($command->data->password);
        $command->data->password = $hash;

        $this->repository->store(User::create($command->data, $this->validator));
    }
}
