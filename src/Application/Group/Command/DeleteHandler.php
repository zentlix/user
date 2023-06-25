<?php

declare(strict_types=1);

namespace Zentlix\User\Application\Group\Command;

use Spiral\Cqrs\Attribute\CommandHandler;
use Zentlix\Core\Domain\Shared\Exception\DomainException;
use Zentlix\User\Domain\Group\DefaultGroups;
use Zentlix\User\Domain\Group\Exception\GroupNotFoundException;
use Zentlix\User\Domain\Group\Repository\GroupRepositoryInterface;
use Zentlix\User\Domain\Group\Specification\ExistsGroupSpecificationInterface;
use Zentlix\User\Domain\Translator\TranslatorInterface;

final readonly class DeleteHandler
{
    public function __construct(
        private GroupRepositoryInterface $repository,
        private ExistsGroupSpecificationInterface $existsGroupSpecification,
        private TranslatorInterface $translator
    ) {
    }

    /**
     * @throws GroupNotFoundException
     * @throws DomainException
     */
    #[CommandHandler]
    public function __invoke(DeleteCommand $command): void
    {
        $this->existsGroupSpecification->isExists($command->group->uuid);

        if (\in_array(
            $command->group->code,
            \array_map(static fn (\BackedEnum $code) => $code->value, DefaultGroups::cases()),
                true
        )) {
            throw new DomainException($this->translator->trans('user.group.cant_delete_default_group'));
        }

        $group = $this->repository->get($command->group->uuid);

        $group->delete();

        $this->repository->store($group);
    }
}
