<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Exception;

class UserAlreadyExistsException extends \InvalidArgumentException
{
    public function __construct(string $message = 'The User already exists.')
    {
        parent::__construct($message);
    }
}
