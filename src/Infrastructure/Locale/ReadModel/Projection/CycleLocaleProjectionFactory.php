<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Locale\ReadModel\Projection;

use Broadway\ReadModel\Projector;
use Spiral\Broadway\EventHandling\Attribute\Listener;
use Zentlix\User\Domain\Locale\Event\LocaleWasCreated;
use Zentlix\User\Domain\Locale\Event\LocaleWasUpdated;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;
use Zentlix\User\Domain\Locale\ReadModel\LocaleView;
use Zentlix\User\Infrastructure\Locale\ReadModel\Repository\CycleLocaleRepository;

#[Listener]
final class CycleLocaleProjectionFactory extends Projector
{
    public function __construct(
        private readonly CycleLocaleRepository $repository
    ) {
    }

    protected function applyLocaleWasCreated(LocaleWasCreated $event): void
    {
        $readModel = new LocaleView();
        $readModel->uuid = $event->data->uuid;
        $readModel->title = $event->data->title;
        $readModel->code = $event->data->getCode();
        $readModel->countryCode = $event->data->getCountryCode();
        $readModel->active = $event->data->active;
        $readModel->sort = $event->data->sort;

        $this->repository->add($readModel);
    }

    /**
     * @throws LocaleNotFoundException
     */
    protected function applyLocaleWasUpdated(LocaleWasUpdated $event): void
    {
        $readModel = $this->repository->getByUuid($event->data->uuid);

        $readModel->title = $event->data->title;
        $readModel->code = $event->data->getCode();
        $readModel->countryCode = $event->data->getCountryCode();
        $readModel->active = $event->data->active;
        $readModel->sort = $event->data->sort;

        $this->repository->apply();
    }
}
