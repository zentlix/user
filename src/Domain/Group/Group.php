<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\Group\DataTransferObject\Group as GroupDTO;
use Zentlix\User\Domain\Group\DataTransferObject\Title as TitleDTO;
use Zentlix\User\Domain\Group\Event\GroupWasCreated;
use Zentlix\User\Domain\Group\Event\GroupWasDeleted;
use Zentlix\User\Domain\Group\Event\GroupWasUpdated;
use Zentlix\User\Domain\Group\Exception\DuplicateCodeException;
use Zentlix\User\Domain\Group\Exception\GroupValidationException;
use Zentlix\User\Domain\Group\Exception\TitleNotFoundException;
use Zentlix\User\Domain\Group\Service\GroupValidatorInterface;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;

final class Group extends EventSourcedAggregateRoot
{
    private UuidInterface $uuid;

    /**
     * @var Title[]
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

    /**
     * @var non-empty-string
     */
    private string $access;

    /**
     * @var non-empty-string[]
     */
    private array $permissions = [];

    /**
     * @throws GroupValidationException
     * @throws DuplicateCodeException
     * @throws LocaleNotFoundException
     */
    public static function create(GroupDTO $data, GroupValidatorInterface $validator): self
    {
        $validator->preCreate($data);

        $self = new self();
        $self->apply(new GroupWasCreated($data));

        return $self;
    }

    /**
     * @throws GroupValidationException
     * @throws LocaleNotFoundException
     * @throws DuplicateCodeException
     */
    public function update(GroupDTO $data, GroupValidatorInterface $validator): void
    {
        $validator->preUpdate($data, $this);

        $this->apply(new GroupWasUpdated($data));
    }

    public function delete(): void
    {
        $this->apply(new GroupWasDeleted($this->uuid, new \DateTimeImmutable()));
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @throws TitleNotFoundException
     */
    public function getTitle(UuidInterface $locale, UuidInterface $fallbackLocale): Title
    {
        return $this->titles[$locale->toString()]
            ?? $this->titles[$fallbackLocale->toString()]
            ?? throw new TitleNotFoundException($locale->toString());
    }

    /**
     * @return non-empty-string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return non-empty-string
     */
    public function getAccess(): string
    {
        return $this->access;
    }

    /**
     * @return non-empty-string[]
     */
    public function getPermissions(): array
    {
        return $this->permissions;
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
        return $this->access === DefaultAccess::Admin->value;
    }

    public function getAggregateRootId(): string
    {
        return $this->uuid->toString();
    }

    protected function applyGroupWasCreated(GroupWasCreated $event): void
    {
        $this->uuid = $event->group->uuid;
        $this->titles = \array_map(static fn (TitleDTO $title): Title => new Title($title), $event->group->getTitles());
        $this->code = $event->group->code;
        $this->access = $event->group->access;
        $this->sort = $event->group->sort;
        if ($this->access === DefaultAccess::Admin->value) {
            $this->permissions = $event->group->permissions;
        }
    }

    protected function applyGroupWasUpdated(GroupWasUpdated $event): void
    {
        $this->titles = \array_map(static fn (TitleDTO $title): Title => new Title($title), $event->group->getTitles());
        $this->code = $event->group->code;
        $this->access = $event->group->access;
        $this->sort = $event->group->sort;
        if ($this->access === DefaultAccess::Admin->value) {
            $this->permissions = $event->group->permissions;
        }
    }
}
