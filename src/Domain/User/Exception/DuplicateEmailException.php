<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Exception;

final class DuplicateEmailException extends UserAlreadyExistsException
{
    public function __construct(string $message = 'The User with this Email already exists.')
    {
        parent::__construct($message);
    }
}
