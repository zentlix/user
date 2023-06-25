<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Shared\ReadModel;

enum Table: string
{
    case Groups = 'zx_groups';
    case GroupTitles = 'zx_group_titles';
    case Locales = 'zx_locales';
    case UserGroups = 'zx_user_groups';
    case Users = 'zx_users';
}
