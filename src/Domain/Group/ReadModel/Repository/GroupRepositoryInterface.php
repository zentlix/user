<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\ReadModel\Repository;

use Ramsey\Uuid\UuidInterface;
use Zentlix\Core\Domain\Shared\ReadModel\Repository\CycleRepositoryInterface;
use Zentlix\User\Domain\Group\Exception\GroupNotFoundException;
use Zentlix\User\Domain\Group\ReadModel\GroupView;

interface GroupRepositoryInterface extends CycleRepositoryInterface
{
    /**
     * @return GroupView[]
     */
    public function findAll(array $scope = [], array $orderBy = []): iterable;

    /**
     * @param UuidInterface|UuidInterface[] $uuid
     *
     * @psalm-return ($uuid is array ? array<GroupView> : ?GroupView)
     */
    public function findByUuid(UuidInterface|array $uuid): GroupView|array|null;

    /**
     * @throws GroupNotFoundException
     */
    public function getByUuid(UuidInterface $uuid): GroupView;

    /**
     * @param non-empty-string $code
     */
    public function findByCode(string $code): ?GroupView;

    /**
     * @param non-empty-string $code
     */
    public function getByCode(string $code): GroupView;
}
