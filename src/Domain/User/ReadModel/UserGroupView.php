<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\ReadModel;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Zentlix\User\Infrastructure\Shared\ReadModel\Table;

#[Entity(role: 'user_group', table: Table::UserGroups->value)]
class UserGroupView
{
    #[Column(type: 'bigPrimary')]
    public int $id;
}
