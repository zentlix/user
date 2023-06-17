<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group;

enum DefaultGroups: string
{
    case Administrators = 'administrators';
    case Users = 'users';
}
