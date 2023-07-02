<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Locale\ReadModel\Projection;

use Broadway\ReadModel\Projector;
use Cycle\ORM\EntityManagerInterface;
use Zentlix\Core\Attribute\ReadModel\Projection;
use Zentlix\Core\ReadEngines;
use Zentlix\User\Domain\Locale\Event\LocaleWasCreated;
use Zentlix\User\Domain\Locale\Event\LocaleWasUpdated;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;
use Zentlix\User\Domain\Locale\ReadModel\LocaleView;
use Zentlix\User\Infrastructure\Locale\ReadModel\Repository\CycleLocaleRepository;

#[Projection(readEngine: ReadEngines::Cycle)]
final class CycleLocaleProjectionFactory extends Projector
{
    public function __construct(
        private readonly CycleLocaleRepository $repository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    protected function applyLocaleWasCreated(LocaleWasCreated $event): void
    {
        $readModel = new LocaleView();
        $readModel->uuid = $event->locale->uuid;
        $readModel->title = $event->locale->title;
        $readModel->code = $event->locale->getCode();
        $readModel->countryCode = $event->locale->getCountryCode();
        $readModel->active = $event->locale->active;
        $readModel->sort = $event->locale->sort;

        $this->entityManager->persist($readModel);
        $this->entityManager->run();
    }

    /**
     * @throws LocaleNotFoundException
     */
    protected function applyLocaleWasUpdated(LocaleWasUpdated $event): void
    {
        $readModel = $this->repository->getByUuid($event->locale->uuid);

        $readModel->title = $event->locale->title;
        $readModel->code = $event->locale->getCode();
        $readModel->countryCode = $event->locale->getCountryCode();
        $readModel->active = $event->locale->active;
        $readModel->sort = $event->locale->sort;

        $this->entityManager->persist($readModel);
        $this->entityManager->run();
    }
}
