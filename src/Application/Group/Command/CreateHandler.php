<?php

declare(strict_types=1);

namespace Zentlix\User\Application\Group\Command;

use Spiral\Cqrs\Attribute\CommandHandler;
use Zentlix\User\Domain\Group\Group;
use Zentlix\User\Domain\Group\Repository\GroupRepositoryInterface;
use Zentlix\User\Domain\Group\Service\GroupValidatorInterface;

final class CreateHandler
{
    public function __construct(
        private readonly GroupRepositoryInterface $groupRepository,
        private readonly GroupValidatorInterface $validator
    ) {
    }

    #[CommandHandler]
    public function __invoke(CreateCommand $command): void
    {
        $this->groupRepository->store(Group::create($command->data, $this->validator));
    }
}
