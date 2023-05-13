<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User;

enum Status: string
{
    case Active = 'active';
    case Blocked = 'blocked';
    case Wait = 'wait';

    public static function typecast(string $value): self
    {
        return self::from($value);
    }
}
