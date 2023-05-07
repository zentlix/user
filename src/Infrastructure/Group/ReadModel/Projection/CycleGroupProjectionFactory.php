<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\ReadModel\Projection;

use Broadway\ReadModel\Projector;
use Spiral\Broadway\EventHandling\Attribute\Listener;
use Zentlix\User\Domain\Group\Event\GroupWasCreated;
use Zentlix\User\Domain\Group\ReadModel\GroupView;
use Zentlix\User\Domain\Group\ReadModel\TitleView;
use Zentlix\User\Infrastructure\Group\ReadModel\Repository\CycleGroupRepository;

#[Listener]
final class CycleGroupProjectionFactory extends Projector
{
    public function __construct(
        private readonly CycleGroupRepository $repository
    ) {
    }

    protected function applyGroupWasCreated(GroupWasCreated $event): void
    {
        $readModel = new GroupView();
        $readModel->uuid = $event->data->uuid;
        $readModel->code = $event->data->code;
        $readModel->role = $event->data->getRole();
        $readModel->rights = $event->data->rights;
        $readModel->sort = $event->data->sort;

        foreach ($event->data->getTitles() as $titleDTO) {
            $title = new TitleView();
            $title->title = $titleDTO->title;
            $title->group = $readModel->uuid;
            $title->locale = $titleDTO->getLocale();

            $readModel->titles->add($title);
        }

        $this->repository->add($readModel);
    }
}
