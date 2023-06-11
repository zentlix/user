<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Locale\Exception;

use Zentlix\Core\Domain\Shared\Exception\DomainException;

class LocaleAlreadyExistsException extends DomainException
{
    public function __construct(string $message = 'The Locale already exists.')
    {
        parent::__construct($message);
    }
}
