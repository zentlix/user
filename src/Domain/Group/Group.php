<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\Group\DataTransferObject\Group as GroupDTO;
use Zentlix\User\Domain\Group\DataTransferObject\GroupTitle as GroupTitleDTO;
use Zentlix\User\Domain\Group\Event\GroupWasCreated;
use Zentlix\User\Domain\Group\Exception\GroupTitleNotFoundException;
use Zentlix\User\Domain\Group\Service\GroupValidatorInterface;

final class Group extends EventSourcedAggregateRoot
{
    private UuidInterface $uuid;

    /**
     * @var GroupTitle[]
     */
    private array $titles;

    /**
     * @var non-empty-string
     */
    private string $code;

    /**
     * @var positive-int
     */
    private int $sort;

    private Role $role;

    private array $rights = [];

    public static function create(GroupDTO $data, GroupValidatorInterface $validator): self
    {
        $validator->preCreate($data);

        $self = new self();
        $self->apply(new GroupWasCreated($data));

        return $self;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @throws GroupTitleNotFoundException
     */
    public function getTitle(UuidInterface $locale, UuidInterface $fallbackLocale): GroupTitle
    {
        return $this->titles[$locale->toString()]
            ?? $this->titles[$fallbackLocale->toString()]
            ?? throw new GroupTitleNotFoundException($locale->toString());
    }

    /**
     * @return non-empty-string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getRights(): array
    {
        return $this->rights;
    }

    /**
     * @return positive-int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param non-empty-string $code
     */
    public function isCodeEqual(string $code): bool
    {
        return $code === $this->code;
    }

    public function isAdminGroup(): bool
    {
        return $this->role->value === Role::ADMIN->value;
    }

    public function isAccessGranted(): bool
    {
        return true; // TODO
    }

    public function getAggregateRootId(): string
    {
        return $this->uuid->toString();
    }

    protected function applyGroupWasCreated(GroupWasCreated $event): void
    {
        $this->uuid = $event->data->uuid;
        $this->titles = array_map(
            static fn (GroupTitleDTO $title): GroupTitle => new GroupTitle($title),
            $event->data->getTitles()
        );
        $this->code = $event->data->code;
        $this->role = $event->data->getRole();
        $this->sort = $event->data->sort;
        $this->rights = $event->data->rights;
    }
}
