<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\User\ReadModel\Projection;

use Broadway\ReadModel\Projector;
use Doctrine\Common\Collections\ArrayCollection;
use Zentlix\Core\Attribute\ReadModel\Projection;
use Zentlix\Core\ReadEngines;
use Zentlix\User\Domain\Group\ReadModel\Repository\GroupRepositoryInterface;
use Zentlix\User\Domain\Locale\ReadModel\Repository\LocaleRepositoryInterface;
use Zentlix\User\Domain\User\Event\UserSignedIn;
use Zentlix\User\Domain\User\Event\UserWasCreated;
use Zentlix\User\Domain\User\ReadModel\UserView;
use Zentlix\User\Infrastructure\User\ReadModel\Repository\CycleUserRepository;

#[Projection(readEngine: ReadEngines::Cycle)]
final class CycleUserProjectionFactory extends Projector
{
    public function __construct(
        private readonly CycleUserRepository $repository,
        private readonly GroupRepositoryInterface $groupRepository,
        private readonly LocaleRepositoryInterface $localeRepository
    ) {
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $readModel = new UserView();
        $readModel->uuid = $event->data->uuid;
        $readModel->password = $event->data->password;
        $readModel->email = $event->data->getEmail();
        $readModel->phone = $event->data->getPhone();
        $readModel->firstName = $event->data->firstName;
        $readModel->lastName = $event->data->lastName;
        $readModel->middleName = $event->data->middleName;
        $readModel->emailConfirmed = $event->data->emailConfirmed;
        $readModel->emailConfirmToken = $event->data->emailConfirmToken;
        $readModel->groups = new ArrayCollection($this->groupRepository->findByUuid($event->data->getGroups()));
        $readModel->status = $event->data->status;
        $readModel->createdAt = $event->data->createdAt;
        $readModel->updatedAt = $event->data->updatedAt;

        if (($localeUuid = $event->data->getLocale()) !== null) {
            $readModel->locale = $this->localeRepository->findByUuid($localeUuid);
        }

        $this->repository->add($readModel);
    }

    protected function applyUserSignedIn(UserSignedIn $event): void
    {
        $user = $this->repository->getByUuid($event->uuid);
        $user->lastLogin = $event->signedInAt;

        $this->repository->apply();
    }
}
