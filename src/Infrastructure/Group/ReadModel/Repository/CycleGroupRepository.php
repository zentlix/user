<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\ReadModel\Repository;

use Cycle\Database\Injection\Parameter;
use Cycle\Database\Query\SelectQuery;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Zentlix\Core\Infrastructure\Shared\ReadModel\Repository\CycleRepository;
use Zentlix\User\Domain\Group\ReadModel\GroupView;
use Zentlix\User\Domain\Group\ReadModel\Repository\CheckGroupByCodeInterface;

/**
 * @method GroupView|null findOne(array $scope = [])
 * @method GroupView|null findByPK($id)
 */
final class CycleGroupRepository extends CycleRepository implements CheckGroupByCodeInterface
{
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
