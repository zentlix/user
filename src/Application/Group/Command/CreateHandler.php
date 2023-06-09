<?php

declare(strict_types=1);

namespace Zentlix\User\Application\Group\Command;

use Spiral\Cqrs\Attribute\CommandHandler;
use Zentlix\User\Domain\Group\Exception\DuplicateCodeException;
use Zentlix\User\Domain\Group\Exception\GroupValidationException;
use Zentlix\User\Domain\Group\Group;
use Zentlix\User\Domain\Group\Repository\GroupRepositoryInterface;
use Zentlix\User\Domain\Group\Service\GroupValidatorInterface;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;

final readonly class CreateHandler
{
    public function __construct(
        private GroupRepositoryInterface $groupRepository,
        private GroupValidatorInterface $validator
    ) {
    }

    /**
     * @throws GroupValidationException
     * @throws DuplicateCodeException
     * @throws LocaleNotFoundException
     */
    #[CommandHandler]
    public function __invoke(CreateCommand $command): void
    {
        $this->groupRepository->store(Group::create($command->data, $this->validator));
    }
}
