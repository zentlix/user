<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\ReadModel\Repository;

use Cycle\Database\Injection\Parameter;
use Cycle\Database\Query\SelectQuery;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Zentlix\Core\Infrastructure\Shared\ReadModel\Repository\CycleRepository;
use Zentlix\User\Domain\Group\Exception\GroupNotFoundException;
use Zentlix\User\Domain\Group\ReadModel\GroupView;
use Zentlix\User\Domain\Group\ReadModel\Repository\CheckGroupByCodeInterface;
use Zentlix\User\Domain\Group\ReadModel\Repository\GroupRepositoryInterface;

/**
 * @method GroupView|null findOne(array $scope = [])
 * @method GroupView|null findByPK($id)
 */
final class CycleGroupRepository extends CycleRepository implements GroupRepositoryInterface, CheckGroupByCodeInterface
{
    public function findByUuid(UuidInterface $uuid): ?GroupView
    {
        return $this->findOne(['uuid' => $uuid]);
    }

    public function getByUuid(UuidInterface $uuid): GroupView
    {
        $group = $this->findByUuid($uuid);

        if (null === $group) {
            throw new GroupNotFoundException(\sprintf('The Group with UUID `%s` not found.', $uuid->toString()));
        }

        return $group;
    }

    /**
     * @param non-empty-string $code
     */
    public function findByCode(string $code): ?GroupView
    {
        return $this->findOne(['code' => $code]);
    }

    /**
     * @param non-empty-string $code
     *
     * @throws GroupNotFoundException
     */
    public function getByCode(string $code): GroupView
    {
        $group = $this->findByCode($code);

        if (null === $group) {
            throw new GroupNotFoundException(\sprintf('The Group with symbol code `%s` not found.', $code));
        }

        return $group;
    }

    /**
     * @param non-empty-string|non-empty-string[] $code
     */
    public function existsCode(string|array $code): array|UuidInterface|null
    {
        $query = $this->getGroupByCodeQueryBuilder($code)->columns('uuid');

        if (\is_string($code)) {
            /** @var ?non-empty-string $result */
            $result = $query->fetchAll()[0]['uuid'] ?? null;

            return $result !== null ? Uuid::fromString($result) : null;
        }

        return \array_map(
            static fn (string $uuid): UuidInterface => Uuid::fromString($uuid),
            \array_column($query->fetchAll(), 'uuid')
        );
    }

    public function add(GroupView $groupRead): void
    {
        $this->register($groupRead);
    }

    /**
     * @param non-empty-string|non-empty-string[] $code
     */
    private function getGroupByCodeQueryBuilder(string|array $code): SelectQuery
    {
        if (\is_string($code)) {
            $code = [$code];
        }

        return $this
            ->select()
            ->buildQuery()
            ->from('zx_groups')
            ->where(['code' => ['in' => new Parameter($code)]]);
    }
}
