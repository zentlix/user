<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\ReadModel\Repository;

use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\Group\Exception\GroupNotFoundException;
use Zentlix\User\Domain\Group\ReadModel\GroupView;

interface GroupRepositoryInterface
{
    /**
     * @return GroupView[]
     */
    public function findAll(array $scope = [], array $orderBy = []): iterable;

    public function findByUuid(UuidInterface $uuid): ?GroupView;

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
