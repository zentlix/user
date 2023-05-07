<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Locale\Specification;

use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;

interface ExistsLocaleSpecificationInterface
{
    /**
     * @param UuidInterface|UuidInterface[] $uuid
     *
     * @throws LocaleNotFoundException
     */
    public function isExists(array|UuidInterface $uuid): bool;
}
