<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\ReadModel;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Ramsey\Uuid\UuidInterface;

#[Entity(role: 'user_group', table: 'zx_user_groups')]
class UserGroupView
{
    #[Column(type: 'uuid', name: 'user_uuid', primary: true)]
    public UuidInterface $userUuid;

    #[Column(type: 'uuid', name: 'group_uuid', primary: true)]
    public UuidInterface $groupUuid;
}
