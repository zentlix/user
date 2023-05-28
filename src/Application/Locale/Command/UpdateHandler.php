<?php

declare(strict_types=1);

namespace Zentlix\User\Application\Locale\Command;

use Spiral\Cqrs\Attribute\CommandHandler;
use Zentlix\User\Domain\Locale\Repository\LocaleRepositoryInterface;
use Zentlix\User\Domain\Locale\Service\LocaleValidatorInterface;

final class UpdateHandler
{
    public function __construct(
        private readonly LocaleRepositoryInterface $repository,
        private readonly LocaleValidatorInterface $validator
    ) {
    }

    #[CommandHandler]
    public function __invoke(UpdateCommand $command): void
    {
        $locale = $this->repository->get($command->data->uuid);

        $locale->update($command->data, $this->validator);

        $this->repository->store($locale);
    }
}
