<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\DataTransferObject;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints;

final class GroupTitle
{
    #[Constraints\Uuid]
    public UuidInterface $uuid;

    /**
     * @var non-empty-string
     */
    #[Constraints\NotBlank]
    #[Constraints\Type('string')]
    public string $title;

    #[Constraints\NotBlank]
    #[Constraints\Uuid]
    private UuidInterface $group;

    #[Constraints\NotBlank]
    #[Constraints\Uuid]
    private UuidInterface $locale;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function getGroup(): UuidInterface
    {
        return $this->group;
    }

    public function setGroup(string|UuidInterface $uuid): self
    {
        $this->group = $uuid instanceof UuidInterface ? $uuid : Uuid::fromString($uuid);

        return $this;
    }

    public function getLocale(): UuidInterface
    {
        return $this->locale;
    }

    public function setLocale(string|Uuid $uuid): self
    {
        $this->locale = $uuid instanceof UuidInterface ? $uuid : Uuid::fromString($uuid);

        return $this;
    }
}
