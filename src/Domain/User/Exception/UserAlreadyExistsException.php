<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Exception;

use Zentlix\Core\Domain\Shared\Exception\DomainException;

class UserAlreadyExistsException extends DomainException
{
    public function __construct(string $message = 'The User already exists.')
    {
        parent::__construct($message);
    }
}
