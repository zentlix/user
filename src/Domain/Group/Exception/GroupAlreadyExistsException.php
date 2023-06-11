<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\Exception;

use Zentlix\Core\Domain\Shared\Exception\DomainException;

class GroupAlreadyExistsException extends DomainException
{
    public function __construct(string $message = 'The Group already exists.')
    {
        parent::__construct($message);
    }
}
