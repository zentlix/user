<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group;

enum DefaultAccess: string
{
    case Admin = 'ADMIN';
    case User = 'USER';
    case Guest = 'GUEST';
}
