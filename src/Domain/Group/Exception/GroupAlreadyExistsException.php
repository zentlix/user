<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\Exception;

class GroupAlreadyExistsException extends \InvalidArgumentException
{
    public function __construct(string $message = 'The Group already exists.')
    {
        parent::__construct($message);
    }
}
