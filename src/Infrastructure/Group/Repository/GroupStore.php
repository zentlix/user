<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\Repository;

use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventSourcing\EventStreamDecorator;
use Broadway\EventStore\EventStore;
use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\Group\Group;
use Zentlix\User\Domain\Group\Repository\GroupRepositoryInterface;

final class GroupStore extends EventSourcingRepository implements GroupRepositoryInterface
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
            Group::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }

    public function store(Group $group): void
    {
        $this->save($group);
    }

    public function get(UuidInterface $uuid): Group
    {
        /** @var Group $group */
        $group = $this->load($uuid->toString());

        return $group;
    }
}
