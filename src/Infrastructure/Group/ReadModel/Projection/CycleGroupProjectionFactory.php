<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\ReadModel\Projection;

use Broadway\ReadModel\Projector;
use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\ORMInterface;
use Zentlix\Core\Attribute\ReadModel\Projection;
use Zentlix\Core\ReadEngines;
use Zentlix\User\Domain\Group\DefaultAccess;
use Zentlix\User\Domain\Group\Event\GroupWasCreated;
use Zentlix\User\Domain\Group\Event\GroupWasDeleted;
use Zentlix\User\Domain\Group\Event\GroupWasUpdated;
use Zentlix\User\Domain\Group\ReadModel\GroupView;
use Zentlix\User\Domain\Group\ReadModel\TitleView;
use Zentlix\User\Infrastructure\Group\ReadModel\Repository\CycleGroupRepository;

#[Projection(readEngine: ReadEngines::Cycle)]
final class CycleGroupProjectionFactory extends Projector
{
    public function __construct(
        private readonly CycleGroupRepository $repository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ORMInterface $orm
    ) {
    }

    protected function applyGroupWasCreated(GroupWasCreated $event): void
    {
        $view = new GroupView();
        $view->uuid = $event->group->uuid;
        $view->code = $event->group->code;
        $view->access = $event->group->access;
        $view->sort = $event->group->sort;
        if ($view->access === DefaultAccess::Admin->value) {
            $view->permissions = $event->group->permissions;
        }

        foreach ($event->group->getTitles() as $title) {
            $view->titles->add(new TitleView($title));
        }

        $this->entityManager->persist($view);
        $this->entityManager->run();
    }

    protected function applyGroupWasUpdated(GroupWasUpdated $event): void
    {
        $view = $this->repository->getByUuid($event->group->uuid);

        $view->code = $event->group->code;
        $view->access = $event->group->access;
        $view->sort = $event->group->sort;
        if ($view->access === DefaultAccess::Admin->value) {
            $view->permissions = $event->group->permissions;
        }

        $view->titles->clear();
        foreach ($this->orm->getRepository(TitleView::class)->findAll(['group' => $view->uuid]) as $title) {
            $this->entityManager->delete($title);
        }
        foreach ($event->group->getTitles() as $title) {
            $view->titles->add(new TitleView($title));
        }

        $this->entityManager->persist($view);
        $this->entityManager->run();
    }

    protected function applyGroupWasDeleted(GroupWasDeleted $event): void
    {
        foreach ($this->orm->getRepository(TitleView::class)->findAll(['group' => $event->uuid]) as $title) {
            $this->entityManager->delete($title);
        }

        $this->entityManager->delete($this->repository->getByUuid($event->uuid));
        $this->entityManager->run();
    }
}
