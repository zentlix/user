<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group;

use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\Group\DataTransferObject\Title as TitleDTO;

class Title
{
    /**
     * @var non-empty-string
     */
    private string $title;

    private UuidInterface $group;

    private UuidInterface $locale;

    public function __construct(TitleDTO $data)
    {
        $this->title = $data->title;
        $this->group = $data->getGroup();
        $this->locale = $data->getLocale();
    }

    /**
     * @return non-empty-string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getGroup(): UuidInterface
    {
        return $this->group;
    }

    public function getLocale(): UuidInterface
    {
        return $this->locale;
    }
}
