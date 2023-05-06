<?php

declare(strict_types=1);

namespace Zentlix\User\Application\Locale\Command;

use Spiral\Cqrs\Attribute\CommandHandler;
use Zentlix\User\Domain\Locale\Locale;
use Zentlix\User\Domain\Locale\Repository\LocaleRepositoryInterface;
use Zentlix\User\Domain\Locale\Service\LocaleValidatorInterface;

final class CreateHandler
{
    public function __construct(
        private readonly LocaleRepositoryInterface $repository,
        private readonly LocaleValidatorInterface $validator
    ) {
    }

    #[CommandHandler]
    public function __invoke(CreateCommand $command): void
    {
        $this->repository->store(Locale::create($command->data, $this->validator));
    }
}
