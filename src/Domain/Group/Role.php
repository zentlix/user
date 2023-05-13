<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group;

enum Role: string
{
    case User = 'ROLE_USER';
    case Admin = 'ROLE_ADMIN';

    public static function typecast(string $value): self
    {
        return self::from($value);
    }
}
