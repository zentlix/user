<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Locale\ReadModel\Repository;

use Cycle\Database\Query\SelectQuery;
use Ramsey\Uuid\UuidInterface;
use Zentlix\Core\Application\Shared\Query\Collection;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;
use Zentlix\User\Domain\Locale\ReadModel\LocaleView;

interface LocaleRepositoryInterface
{
    /**
     * @param positive-int $page
     * @param positive-int $limit
     * @param non-empty-array<non-empty-string, non-empty-string> $orderBy
     */
    public function page(int $page, int $limit, array $orderBy = ['sort' => SelectQuery::SORT_ASC]): Collection;

    /**
     * @return LocaleView[]
     */
    public function findAll(array $scope = [], array $orderBy = ['sort' => SelectQuery::SORT_ASC]): iterable;

    /**
     * @return LocaleView[]
     */
    public function findActive(array $orderBy = ['sort' => SelectQuery::SORT_ASC]): iterable;

    public function findByUuid(UuidInterface $uuid): ?LocaleView;

    /**
     * @throws LocaleNotFoundException
     */
    public function getByUuid(UuidInterface $uuid): LocaleView;

    /**
     * @param non-empty-string $fullCode
     */
    public function findByFullCode(string $fullCode): ?LocaleView;

    /**
     * @param non-empty-string $fullCode
     *
     * @throws LocaleNotFoundException
     */
    public function getByFullCode(string $fullCode): LocaleView;

    /**
     * @param non-empty-string $code
     */
    public function findByCode(string $code): ?LocaleView;

    /**
     * @param non-empty-string $code
     *
     * @throws LocaleNotFoundException
     */
    public function getByCode(string $code): LocaleView;

    public function isAvailable(): bool;
}
