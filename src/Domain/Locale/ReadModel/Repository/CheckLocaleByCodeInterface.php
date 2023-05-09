<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Locale\ReadModel\Repository;

use Ramsey\Uuid\UuidInterface;

interface CheckLocaleByCodeInterface
{
    /**
     * @param non-empty-string|non-empty-string[] $code
     *
     * @psalm-return ($code is array ? array : ?UuidInterface)
     */
    public function existsCode(array|string $code): array|UuidInterface|null;
}
