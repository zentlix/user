<?php

declare(strict_types=1);

namespace Zentlix\User\Application\Group\Command;

use Spiral\Cqrs\Attribute\CommandHandler;
use Zentlix\User\Domain\Group\Exception\DuplicateCodeException;
use Zentlix\User\Domain\Group\Exception\GroupValidationException;
use Zentlix\User\Domain\Group\Repository\GroupRepositoryInterface;
use Zentlix\User\Domain\Group\Service\GroupValidatorInterface;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;

final readonly class UpdateHandler
{
    public function __construct(
        private GroupRepositoryInterface $repository,
        private GroupValidatorInterface $validator
    ) {
    }

    /**
     * @throws GroupValidationException
     * @throws DuplicateCodeException
     * @throws LocaleNotFoundException
     */
    #[CommandHandler]
    public function __invoke(UpdateCommand $command): void
    {
        $group = $this->repository->get($command->data->uuid);

        $group->update($command->data, $this->validator);

        $this->repository->store($group);
    }
}
