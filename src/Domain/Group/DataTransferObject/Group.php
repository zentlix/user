<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\DataTransferObject;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints;
use Zentlix\User\Domain\Group\DefaultGroups;
use Zentlix\User\Domain\Group\ReadModel\GroupView;

final class Group
{
    #[Constraints\Uuid]
    public UuidInterface $uuid;

    /**
     * @var non-empty-string
     */
    #[Constraints\NotBlank]
    #[Constraints\Type('string')]
    public string $code;

    /**
     * @var positive-int
     */
    #[Constraints\NotBlank]
    #[Constraints\Positive]
    #[Constraints\Type('int')]
    public int $sort = 1;

    /**
     * @var non-empty-string
     */
    #[Constraints\NotBlank]
    public string $access;

    /**
     * @var non-empty-string[]
     */
    #[Constraints\Type('array')]
    public array $permissions = [];

    /**
     * @var Title[]
     */
    #[Constraints\NotBlank]
    #[Constraints\Type('array')]
    private array $titles;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->access = DefaultGroups::Administrators->value;
    }

    /**
     * @param non-empty-string $title
     * @param non-empty-string|UuidInterface $locale
     */
    public function setTitle(string $title, string|UuidInterface $locale): self
    {
        $lang = new Title();
        $lang->title = $title;
        $lang->setLocale($locale);
        $lang->setGroup($this->uuid);

        $this->titles[] = $lang;

        return $this;
    }

    /**
     * @return Title[]
     */
    public function getTitles(): array
    {
        return $this->titles;
    }

    public static function fromGroup(GroupView $group): self
    {
        $self = new self();
        $self->uuid = $group->uuid;
        $self->code = $group->code;
        $self->sort = $group->sort;
        $self->access = $group->access;
        $self->permissions = $group->permissions;
        foreach ($group->getTitles() as $title) {
            $self->setTitle($title->title, $title->locale);
        }

        return $self;
    }
}
