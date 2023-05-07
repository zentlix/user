<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Locale\ReadModel\Repository;

use Cycle\Database\Injection\Parameter;
use Cycle\Database\Query\SelectQuery;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Spiral\Pagination\Paginator;
use Zentlix\Core\Application\Shared\Query\Collection;
use Zentlix\Core\Infrastructure\Shared\ReadModel\Repository\CycleRepository;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;
use Zentlix\User\Domain\Locale\ReadModel\LocaleView;
use Zentlix\User\Domain\Locale\ReadModel\Repository\CheckLocaleByCodeInterface;
use Zentlix\User\Domain\Locale\ReadModel\Repository\CheckLocaleInterface;
use Zentlix\User\Domain\Locale\ReadModel\Repository\LocaleRepositoryInterface;

/**
 * @method LocaleView|null findOne(array $scope = [])
 * @method LocaleView|null findByPK($id)
 */
final class CycleLocaleRepository extends CycleRepository implements LocaleRepositoryInterface, CheckLocaleInterface, CheckLocaleByCodeInterface
{
    /**
     * @param non-empty-string|non-empty-string[] $code
     */
    public function existsCode(string|array $code): array|UuidInterface|null
    {
        $query = $this->getLocaleByCodeQueryBuilder($code)->columns('uuid');

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

    public function add(LocaleView $localeRead): void
    {
        $this->register($localeRead);
    }

    /**
     * @return LocaleView[]
     */
    public function findAll(array $scope = [], array $orderBy = ['sort' => SelectQuery::SORT_ASC]): iterable
    {
        /** @var LocaleView[] $result */
        $result = parent::findAll($scope, $orderBy);

        return $result;
    }

    public function findByUuid(UuidInterface $uuid): ?LocaleView
    {
        return $this->findOne(['uuid' => $uuid]);
    }

    /**
     * @throws LocaleNotFoundException
     */
    public function getByUuid(UuidInterface $uuid): LocaleView
    {
        $locale = $this->findByUuid($uuid);

        if (null === $locale) {
            throw new LocaleNotFoundException(sprintf('The Locale with UUID `%s` not found.', $uuid->toString()));
        }

        return $locale;
    }

    /**
     * @return LocaleView[]
     */
    public function findActive(array $orderBy = ['sort' => SelectQuery::SORT_ASC]): iterable
    {
        /** @var LocaleView[] $locales */
        $locales = $this->findAll(['active' => true], $orderBy);

        return $locales;
    }

    /**
     * @param positive-int $page
     * @param positive-int $limit
     * @param non-empty-array<non-empty-string, non-empty-string> $orderBy
     */
    public function page(int $page, int $limit, array $orderBy = ['sort' => SelectQuery::SORT_ASC]): Collection
    {
        $query = $this
            ->active()
            ->select()
            ->orderBy($orderBy);

        $paginator = new Paginator($limit);
        $paginator->withPage($page)->paginate($query);

        $count = $query->count('uuid');

        return new Collection(
            page: $page,
            limit: $limit,
            total: $count,
            orderBy: \array_key_first($orderBy),
            direction: $orderBy[\array_key_first($orderBy)],
            items: $query->getIterator()
        );
    }

    public function exists(array|UuidInterface $uuid): UuidInterface|array|null
    {
        $query = $this->getLocaleQueryBuilder($uuid)->columns('uuid');

        if ($uuid instanceof UuidInterface) {
            /** @var ?non-empty-string $result */
            $result = $query->fetchAll()[0]['uuid'] ?? null;

            return $result !== null ? Uuid::fromString($result) : null;
        }

        return \array_map(
            static fn (string $uuid): UuidInterface => Uuid::fromString($uuid),
            \array_column($query->fetchAll(), 'uuid')
        );
    }

    /**
     * @param non-empty-string $fullCode
     *
     * @throws LocaleNotFoundException
     */
    public function getByFullCode(string $fullCode): LocaleView
    {
        /** @var array{0: non-empty-string, 1: non-empty-string} $codes */
        $codes = \explode('_', $fullCode);

        $locale = $this->findOne(['code' => $codes[0], 'country_code' => $codes[1]]);

        if (null === $locale) {
            throw new LocaleNotFoundException(\sprintf('The Locale with code `%s` not found.', $fullCode));
        }

        return $locale;
    }

    /**
     * @param non-empty-string $code
     *
     * @throws LocaleNotFoundException
     */
    public function getByCode(string $code): LocaleView
    {
        $locale = $this->findOne(['code' => $code]);

        if (null === $locale) {
            throw new LocaleNotFoundException(\sprintf('The Locale with code `%s` not found.', $code));
        }

        return $locale;
    }

    public function active(): self
    {
        $repository = clone $this;
        $repository->select->where(['active' => true]);

        return $repository;
    }

    /**
     * @param non-empty-string|non-empty-string[] $code
     */
    private function getLocaleByCodeQueryBuilder(string|array $code): SelectQuery
    {
        if (is_string($code)) {
            $code = [$code];
        }

        return $this
            ->select()
            ->buildQuery()
            ->from('zx_locales')
            ->where(['code' => ['in' => new Parameter($code)]]);
    }

    /**
     * @param UuidInterface|UuidInterface[] $uuid
     */
    private function getLocaleQueryBuilder(UuidInterface|array $uuid): SelectQuery
    {
        if ($uuid instanceof UuidInterface) {
            $uuid = [$uuid];
        }

        return $this
            ->select()
            ->buildQuery()
            ->from('zx_locales')
            ->where(['uuid' => ['in' => new Parameter($uuid)]]);
    }
}
