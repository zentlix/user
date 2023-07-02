<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\DataTransferObject;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Spiral\Marshaller\Meta\Marshal;
use Symfony\Component\Validator\Constraints;

final class Title
{
    /**
     * @var non-empty-string
     */
    #[Constraints\NotBlank]
    #[Constraints\Type('string')]
    #[Marshal]
    public string $title;

    #[Constraints\NotBlank]
    #[Constraints\Uuid]
    private UuidInterface $group;

    #[Constraints\NotBlank]
    #[Constraints\Uuid]
    private UuidInterface $locale;

    public function getGroup(): UuidInterface
    {
        return $this->group;
    }

    /**
     * @param non-empty-string|UuidInterface $uuid
     */
    public function setGroup(string|UuidInterface $uuid): self
    {
        $this->group = $uuid instanceof UuidInterface ? $uuid : Uuid::fromString($uuid);

        return $this;
    }

    public function getLocale(): UuidInterface
    {
        return $this->locale;
    }

    /**
     * @param non-empty-string|UuidInterface $uuid
     */
    public function setLocale(string|UuidInterface $uuid): self
    {
        $this->locale = $uuid instanceof UuidInterface ? $uuid : Uuid::fromString($uuid);

        return $this;
    }
}
