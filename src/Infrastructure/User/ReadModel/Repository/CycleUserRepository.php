<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\User\ReadModel\Repository;

use Cycle\Database\Injection\Parameter;
use Cycle\Database\Query\SelectQuery;
use libphonenumber\PhoneNumber;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Spiral\AdminPanel\Security\CredentialsInterface;
use Spiral\Auth\TokenInterface;
use Zentlix\Core\Infrastructure\Shared\ReadModel\Repository\CycleRepository;
use Zentlix\User\Domain\User\Exception\UserNotFoundException;
use Zentlix\User\Domain\User\ReadModel\Repository\CheckUserByEmailInterface;
use Zentlix\User\Domain\User\ReadModel\Repository\CheckUserByPhoneInterface;
use Zentlix\User\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Zentlix\User\Domain\User\ReadModel\UserView;
use Zentlix\User\Domain\User\ValueObject\Email;

/**
 * @method UserView|null findOne(array $scope = [])
 * @method UserView|null findByPK($id)
 */
final class CycleUserRepository extends CycleRepository implements UserRepositoryInterface, CheckUserByEmailInterface, CheckUserByPhoneInterface
{
    public function getActor(TokenInterface $token): ?UserView
    {
        $data = $token->getPayload();

        if (!isset($data['userID'])) {
            return null;
        }

        return $this->findByUuid(Uuid::fromString($data['userID']));
    }

    public function findByCredentials(CredentialsInterface $credentials): ?UserView
    {
        return $this->findByEmail(Email::fromString($credentials->getUserIdentifier()));
    }

    /**
     * @param UuidInterface|UuidInterface[] $uuid
     *
     * @psalm-return ($uuid is array ? array : ?UserView)
     */
    public function findByUuid(UuidInterface|array $uuid): UserView|array|null
    {
        if (\is_array($uuid)) {
            return $this->findAll(['uuid' => ['in' => new Parameter($uuid)]]);
        }

        return $this->findOne(['uuid' => $uuid]);
    }

    /**
     * @throws UserNotFoundException
     */
    public function getByUuid(UuidInterface $uuid): UserView
    {
        $user = $this->findByUuid($uuid);

        if (null === $user) {
            throw new UserNotFoundException(\sprintf('The User with UUID `%s` not found.', $uuid->toString()));
        }

        return $user;
    }

    /**
     * @param Email|Email[] $email
     *
     * @psalm-return ($email is array ? array : ?UserView)
     */
    public function findByEmail(Email|array $email): UserView|array|null
    {
        if (\is_array($email)) {
            return $this->findAll(['email' => ['in' => new Parameter($email)]]);
        }

        return $this->findOne(['email' => $email]);
    }

    /**
     * @param Email|Email[] $email
     *
     * @psalm-return ($email is array ? array : ?UuidInterface)
     */
    public function existsEmail(Email|array $email): array|UuidInterface|null
    {
        return $this->fetchUuid($this->getUserByEmailQueryBuilder($email)->columns('uuid'), \is_array($email));
    }

    /**
     * @param PhoneNumber|PhoneNumber[] $phone
     *
     * @psalm-return ($phone is array ? array : ?UuidInterface)
     */
    public function existsPhone(PhoneNumber|array $phone): array|UuidInterface|null
    {
        return $this->fetchUuid($this->getUserByPhoneQueryBuilder($phone)->columns('uuid'), \is_array($phone));
    }

    public function add(UserView $userRead): void
    {
        $this->register($userRead);
    }

    /**
     * @param Email|Email[] $email
     */
    private function getUserByEmailQueryBuilder(Email|array $email): SelectQuery
    {
        if (!\is_array($email)) {
            $email = [$email];
        }

        return $this
            ->select()
            ->buildQuery()
            ->from('zx_users')
            ->where(['email' => ['in' => new Parameter($email)]]);
    }

    /**
     * @param PhoneNumber|PhoneNumber[] $phone
     */
    private function getUserByPhoneQueryBuilder(PhoneNumber|array $phone): SelectQuery
    {
        if (!\is_array($phone)) {
            $phone = [$phone];
        }

        return $this
            ->select()
            ->buildQuery()
            ->from('zx_users')
            ->where(['phone' => ['in' => new Parameter($phone)]]);
    }
}
