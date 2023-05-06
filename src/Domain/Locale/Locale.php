<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Locale;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\Locale\DataTransferObject\Locale as LocaleDTO;
use Zentlix\User\Domain\Locale\Event\LocaleWasCreated;
use Zentlix\User\Domain\Locale\Event\LocaleWasUpdated;
use Zentlix\User\Domain\Locale\Service\LocaleValidatorInterface;

final class Locale extends EventSourcedAggregateRoot
{
    private UuidInterface $uuid;

    /**
     * @var non-empty-string
     */
    private string $title;

    /**
     * @var non-empty-string
     */
    private string $code;

    /**
     * @var non-empty-string
     */
    private string $countryCode;

    private bool $active;

    /**
     * @var positive-int
     */
    private int $sort;

    public static function create(LocaleDTO $data, LocaleValidatorInterface $validator): self
    {
        $validator->preCreate($data);

        $locale = new self();
        $locale->apply(new LocaleWasCreated($data));

        return $locale;
    }

    public function update(LocaleDTO $data, LocaleValidatorInterface $validator): void
    {
        $validator->preUpdate($data, $this);

        $this->apply(new LocaleWasUpdated($data));
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @return non-empty-string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Returns an ISO 639-1 code, such as en.
     *
     * @return non-empty-string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Returns an ISO 3166-1 alpha-2 country code, such as FR.
     *
     * @return non-empty-string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * Returns language and country code, such as fr_FR.
     *
     * @return non-empty-string
     */
    public function getFullCode(): string
    {
        return $this->getCode().'_'.$this->getCountryCode();
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return positive-int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    public function getAggregateRootId(): string
    {
        return $this->uuid->toString();
    }

    protected function applyLocaleWasCreated(LocaleWasCreated $event): void
    {
        $this->uuid = $event->data->uuid;
        $this->title = $event->data->title;
        $this->code = $event->data->getCode();
        $this->countryCode = $event->data->getCountryCode();
        $this->active = $event->data->active;
        $this->sort = $event->data->sort;
    }

    protected function applyLocaleWasUpdated(LocaleWasUpdated $event): void
    {
        $this->title = $event->data->title;
        $this->code = $event->data->getCode();
        $this->countryCode = $event->data->getCountryCode();
        $this->active = $event->data->active;
        $this->sort = $event->data->sort;
    }
}
