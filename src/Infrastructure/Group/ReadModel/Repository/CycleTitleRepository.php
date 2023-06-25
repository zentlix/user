<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\ReadModel\Repository;

use Ramsey\Uuid\UuidInterface;
use Zentlix\Core\Infrastructure\Shared\ReadModel\Repository\CycleRepository;
use Zentlix\User\Domain\Group\ReadModel\Repository\TitleRepositoryInterface;
use Zentlix\User\Domain\Group\ReadModel\TitleView;
use Zentlix\User\Infrastructure\Shared\ReadModel\Table;

final class CycleTitleRepository extends CycleRepository implements TitleRepositoryInterface
{
    /**
     * @return array<TitleView>
     */
    public function findByGroupUuid(UuidInterface $groupUuid): iterable
    {
        return $this->findAll(['group' => $groupUuid->toString()]);
    }

    protected function getTable(): string
    {
        return Table::GroupTitles->value;
    }
}
