<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Locale\Repository;

use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventSourcing\EventStreamDecorator;
use Broadway\EventStore\EventStore;
use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\Locale\Locale;
use Zentlix\User\Domain\Locale\Repository\LocaleRepositoryInterface;

final class LocaleStore extends EventSourcingRepository implements LocaleRepositoryInterface
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
            Locale::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }

    public function store(Locale $locale): void
    {
        $this->save($locale);
    }

    public function get(UuidInterface $uuid): Locale
    {
        /** @var Locale $locale */
        $locale = $this->load($uuid->toString());

        return $locale;
    }
}
