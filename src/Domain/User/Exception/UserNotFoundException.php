<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Exception;

use Zentlix\Core\Domain\Shared\Exception\NotFoundException;

class UserNotFoundException extends NotFoundException
{
    public function __construct(string $message = 'The User does not exist.')
    {
        parent::__construct($message);
    }
}
