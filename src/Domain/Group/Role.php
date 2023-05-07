<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group;

enum Role: string
{
    case USER = 'ROLE_USER';
    case ADMIN = 'ROLE_ADMIN';
}
