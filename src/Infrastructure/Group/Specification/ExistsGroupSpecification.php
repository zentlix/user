<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\Specification;

use Ramsey\Uuid\UuidInterface;
use Spiral\Translator\TranslatorInterface;
use Zentlix\Core\Infrastructure\Shared\Specification\AbstractSpecification;
use Zentlix\User\Domain\Group\Exception\GroupNotFoundException;
use Zentlix\User\Domain\Group\ReadModel\Repository\CheckGroupInterface;
use Zentlix\User\Domain\Group\Specification\ExistsGroupSpecificationInterface;

final class ExistsGroupSpecification extends AbstractSpecification implements ExistsGroupSpecificationInterface
{
    public function __construct(
        private readonly CheckGroupInterface $checkUserGroup,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * @param UuidInterface|UuidInterface[] $uuid
     *
     * @throws GroupNotFoundException
     */
    public function isExists(array|UuidInterface $uuid): bool
    {
        return $this->isSatisfiedBy($uuid);
    }

    /**
     * @param UuidInterface|UuidInterface[] $value
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     *
     * @throws GroupNotFoundException
     */
    public function isSatisfiedBy($value): bool
    {
        if ($value instanceof UuidInterface) {
            $value = [$value];
        }

        $groups = \array_map(
            static fn (UuidInterface $uuid) => $uuid->toString(),
            $this->checkUserGroup->exists($value)
        );

        foreach ($value as $uuid) {
            if (!\in_array($uuid->toString(), $groups, true)) {
                throw new GroupNotFoundException(
                    $this->translator->trans('user.group.group_is_not_exists', ['uuid' => $uuid->toString()])
                );
            }
        }

        return true;
    }
}
