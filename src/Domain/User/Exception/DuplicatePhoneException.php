<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Exception;

final class DuplicatePhoneException extends UserAlreadyExistsException
{
    public function __construct(string $message = 'The User with this phone number already exists.')
    {
        parent::__construct($message);
    }
}
