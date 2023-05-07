<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\ReadModel\Repository;

use Ramsey\Uuid\UuidInterface;

interface CheckGroupByCodeInterface
{
    /**
     * @param non-empty-string|non-empty-string[] $code
     */
    public function existsCode(array|string $code): array|UuidInterface|null;
}
