<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\ReadModel\Repository;

use Cycle\Database\Injection\Parameter;
use Cycle\Database\Query\SelectQuery;
use Cycle\ORM\Select;
use Ramsey\Uuid\UuidInterface;
use Zentlix\Core\Infrastructure\Shared\ReadModel\Repository\CycleRepository;
use Zentlix\User\Domain\Group\Exception\GroupNotFoundException;
use Zentlix\User\Domain\Group\ReadModel\GroupView;
use Zentlix\User\Domain\Group\ReadModel\Repository\CheckGroupByCodeInterface;
use Zentlix\User\Domain\Group\ReadModel\Repository\CheckGroupInterface;
use Zentlix\User\Domain\Group\ReadModel\Repository\GroupRepositoryInterface;
use Zentlix\User\Infrastructure\Shared\ReadModel\Table;

final class CycleGroupRepository extends CycleRepository implements GroupRepositoryInterface, CheckGroupInterface, CheckGroupByCodeInterface
{
    /**
     * @param UuidInterface|UuidInterface[] $uuid
     *
     * @psalm-return ($uuid is array ? GroupView[] : GroupView|null)
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
     * @psalm-return ($uuid is array ? UuidInterface[] : UuidInterface|null)
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

    public function findOne(array $scope = []): ?GroupView
    {
        return $this->withLocalized()->fetchOne($scope);
    }

    public function withLocalized(): Select
    {
        return $this
            ->select()
            ->with('title', [
                'where' => ['locale' => $this->currentLocale->getId()]
            ]);
    }

    protected function getTable(): string
    {
        return Table::Groups->value;
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
            ->from(Table::Groups->value)
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
            ->from(Table::Groups->value)
            ->where(['code' => ['in' => new Parameter($code)]]);
    }
}
