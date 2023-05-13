<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\User\Repository;

use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventSourcing\EventStreamDecorator;
use Broadway\EventStore\EventStore;
use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\User\Repository\UserRepositoryInterface;
use Zentlix\User\Domain\User\User;

final class UserStore extends EventSourcingRepository implements UserRepositoryInterface
{
    /**
     * @param EventStreamDecorator[] $eventStreamDecorators
     */
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = []
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            User::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }

    public function store(User $user): void
    {
        $this->save($user);
    }

    public function get(UuidInterface $uuid): User
    {
        /** @var User $user */
        $user = $this->load($uuid->toString());

        return $user;
    }
}
