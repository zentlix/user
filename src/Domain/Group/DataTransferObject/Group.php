<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\DataTransferObject;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints;
use Zentlix\User\Domain\Group\Role;

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

    private Role $role = Role::USER;

    #[Constraints\Type('array')]
    public array $rights = [];

    /**
     * @var GroupTitle[]
     */
    #[Constraints\NotBlank]
    #[Constraints\Type('array')]
    private array $titles;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    /**
     * @param non-empty-string $title
     * @param non-empty-string|UuidInterface $locale
     */
    public function setTitle(string $title, string|UuidInterface $locale): self
    {
        $lang = new GroupTitle();
        $lang->title = $title;
        $lang->setLocale($locale);
        $lang->setGroup($this->uuid);

        $this->titles[] = $lang;

        return $this;
    }

    /**
     * @return GroupTitle[]
     */
    public function getTitles(): array
    {
        return $this->titles;
    }

    /**
     * @param non-empty-string|Role $role
     */
    public function setRole(string|Role $role): void
    {
        $this->role = \is_string($role) ? Role::from($role) : $role;
    }

    public function getRole(): Role
    {
        return $this->role;
    }
}
