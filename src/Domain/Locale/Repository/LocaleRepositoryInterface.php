<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Locale\Repository;

use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\Locale\Locale;

interface LocaleRepositoryInterface
{
    public function get(UuidInterface $uuid): Locale;

    public function store(Locale $locale): void;
}
