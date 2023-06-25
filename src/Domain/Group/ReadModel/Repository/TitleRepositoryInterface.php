<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\ReadModel\Repository;

use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\Group\ReadModel\TitleView;

interface TitleRepositoryInterface
{
    /**
     * @return array<TitleView>
     */
    public function findByGroupUuid(UuidInterface $groupUuid): iterable;
}
