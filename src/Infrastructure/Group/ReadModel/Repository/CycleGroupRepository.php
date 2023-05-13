<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\ReadModel\Repository;

use Cycle\Database\Injection\Parameter;
use Cycle\Database\Query\SelectQuery;
use Ramsey\Uuid\UuidInterface;
use Zentlix\Core\Infrastructure\Shared\ReadModel\Repository\CycleRepository;
use Zentlix\User\Domain\Group\Exception\GroupNotFoundException;
use Zentlix\User\Domain\Group\ReadModel\GroupView;
use Zentlix\User\Domain\Group\ReadModel\Repository\CheckGroupByCodeInterface;
use Zentlix\User\Domain\Group\ReadModel\Repository\CheckGroupInterface;
use Zentlix\User\Domain\Group\ReadModel\Repository\GroupRepositoryInterface;

/**
 * @method GroupView|null findOne(array $scope = [])
 * @method GroupView|null findByPK($id)
 */
final class CycleGroupRepository extends CycleRepository implements GroupRepositoryInterface, CheckGroupInterface, CheckGroupByCodeInterface
{
    /**
     * @param UuidInterface|UuidInterface[] $uuid
     *
     * @psalm-return ($uuid is array ? array : ?GroupView)
     */
    public function findByUuid(UuidInterface|array $uuid): GroupView|array|null
    {
        if (\is_array($uuid)) {
            return $this->findAll(['uuid' => ['in' => new Parameter($uuid)]]);
        }

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
     * @param UuidInterface|UuidInterface[] $uuid
     *
     * @psalm-return ($uuid is array ? array : ?UuidInterface)
     */
    public function exists(UuidInterface|array $uuid): UuidInterface|array|null
    {
        return $this->fetchUuid($this->getGroupQueryBuilder($uuid)->columns('uuid'), \is_array($uuid));
    }

    /**
     * @param non-empty-string|non-empty-string[] $code
     */
    public function existsCode(string|array $code): array|UuidInterface|null
    {
        return $this->fetchUuid($this->getGroupByCodeQueryBuilder($code)->columns('uuid'), \is_array($code));
    }

    public function add(GroupView $groupRead): void
    {
        $this->register($groupRead);
    }

    /**
     * @param UuidInterface|UuidInterface[] $uuid
     */
    private function getGroupQueryBuilder(UuidInterface|array $uuid): SelectQuery
    {
        if ($uuid instanceof UuidInterface) {
            $uuid = [$uuid];
        }

        return $this
            ->select()
            ->buildQuery()
            ->from('zx_groups')
            ->where(['uuid' => ['in' => new Parameter($uuid)]]);
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
