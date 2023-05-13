<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\ReadModel\Repository;

use Cycle\Database\Query\SelectQuery;
use Ramsey\Uuid\UuidInterface;
use Spiral\Filament\Security\UserProviderInterface;
use Zentlix\User\Domain\User\Exception\UserNotFoundException;
use Zentlix\User\Domain\User\ReadModel\UserView;
use Zentlix\User\Domain\User\ValueObject\Email;

interface UserRepositoryInterface extends UserProviderInterface
{
    /**
     * @return UserView[]
     */
    public function findAll(array $scope = [], array $orderBy = ['createdAt' => SelectQuery::SORT_DESC]): iterable;

    /**
     * @param UuidInterface|UuidInterface[] $uuid
     *
     * @psalm-return ($uuid is array ? array : ?UserView)
     */
    public function findByUuid(UuidInterface|array $uuid): UserView|array|null;

    /**
     * @throws UserNotFoundException
     */
    public function getByUuid(UuidInterface $uuid): UserView;

    /**
     * @param Email|Email[] $email
     *
     * @psalm-return ($email is array ? array : ?UserView)
     */
    public function findByEmail(Email|array $email): UserView|array|null;
}
