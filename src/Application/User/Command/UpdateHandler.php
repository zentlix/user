<?php

declare(strict_types=1);

namespace Zentlix\User\Application\User\Command;

use Spiral\Cqrs\Attribute\CommandHandler;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Zentlix\User\Domain\Group\Exception\GroupNotFoundException;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;
use Zentlix\User\Domain\User\Exception\DuplicateEmailException;
use Zentlix\User\Domain\User\Exception\DuplicatePhoneException;
use Zentlix\User\Domain\User\Exception\UserValidationException;
use Zentlix\User\Domain\User\Exception\UserWithoutGroupException;
use Zentlix\User\Domain\User\Repository\UserRepositoryInterface;
use Zentlix\User\Domain\User\Service\UserValidatorInterface;
use Zentlix\User\Domain\User\User;

final readonly class UpdateHandler
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private UserValidatorInterface $validator,
        private PasswordHasherFactoryInterface $passwordHasherFactory
    ) {
    }

    /**
     * @throws UserValidationException
     * @throws UserWithoutGroupException
     * @throws DuplicateEmailException
     * @throws DuplicatePhoneException
     * @throws GroupNotFoundException
     * @throws LocaleNotFoundException
     */
    #[CommandHandler]
    public function __invoke(UpdateCommand $command): void
    {
        $user = $this->repository->get($command->user->uuid);

        if (!empty($command->user->password)) {
            /** @var non-empty-string $hash */
            $hash = $this->passwordHasherFactory->getPasswordHasher(User::class)->hash($command->user->password);
            $command->user->password = $hash;
        }

        $user->update($command->user, $this->validator);

        $this->repository->store($user);
    }
}
