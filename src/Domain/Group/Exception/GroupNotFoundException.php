<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\Exception;

use Zentlix\Core\Domain\Shared\Exception\NotFoundException;

class GroupNotFoundException extends NotFoundException
{
    public function __construct(string $message = 'The Group does not exist.')
    {
        parent::__construct($message);
    }
}
