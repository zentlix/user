<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\ReadModel\Projection;

use Broadway\ReadModel\Projector;
use Cycle\ORM\EntityManagerInterface;
use Zentlix\Core\Attribute\ReadModel\Projection;
use Zentlix\Core\ReadEngines;
use Zentlix\User\Domain\Group\DefaultAccess;
use Zentlix\User\Domain\Group\Event\GroupWasCreated;
use Zentlix\User\Domain\Group\Event\GroupWasDeleted;
use Zentlix\User\Domain\Group\Event\GroupWasUpdated;
use Zentlix\User\Domain\Group\ReadModel\GroupView;
use Zentlix\User\Domain\Group\ReadModel\TitleView;
use Zentlix\User\Infrastructure\Group\ReadModel\Repository\CycleGroupRepository;
use Zentlix\User\Infrastructure\Group\ReadModel\Repository\CycleTitleRepository;

#[Projection(readEngine: ReadEngines::Cycle)]
final class CycleGroupProjectionFactory extends Projector
{
    public function __construct(
        private readonly CycleGroupRepository $repository,
        private readonly CycleTitleRepository $titleRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    protected function applyGroupWasCreated(GroupWasCreated $event): void
    {
        $readModel = new GroupView();
        $readModel->uuid = $event->data->uuid;
        $readModel->code = $event->data->code;
        $readModel->access = $event->data->access;
        $readModel->sort = $event->data->sort;
        if ($readModel->access === DefaultAccess::Admin->value) {
            $readModel->permissions = $event->data->permissions;
        }

        foreach ($event->data->getTitles() as $titleDTO) {
            $title = new TitleView();
            $title->title = $titleDTO->title;
            $title->group = $readModel->uuid;
            $title->locale = $titleDTO->getLocale();

            $readModel->getTitles()->add($title);
        }

        $this->entityManager->persist($readModel);
        $this->entityManager->run();
    }

    protected function applyGroupWasUpdated(GroupWasUpdated $event): void
    {
        $readModel = $this->repository->getByUuid($event->data->uuid);

        $readModel->code = $event->data->code;
        $readModel->access = $event->data->access;
        $readModel->sort = $event->data->sort;
        if ($readModel->access === DefaultAccess::Admin->value) {
            $readModel->permissions = $event->data->permissions;
        }

        foreach ($this->titleRepository->findByGroupUuid($event->data->uuid) as $title) {
            foreach ($event->data->getTitles() as $titleDTO) {
                if ($title->locale->equals($titleDTO->getLocale())) {
                    $title->title = $titleDTO->title;
                }
            }
        }

        $this->entityManager->persist($readModel);
        $this->entityManager->run();
    }

    protected function applyGroupWasDeleted(GroupWasDeleted $event): void
    {
        $this->entityManager->delete($this->repository->getByUuid($event->uuid));
        $this->entityManager->run();

        foreach ($this->titleRepository->findByGroupUuid($event->uuid) as $title) {
            $this->entityManager->delete($title);
        }
        $this->entityManager->run();
    }
}
