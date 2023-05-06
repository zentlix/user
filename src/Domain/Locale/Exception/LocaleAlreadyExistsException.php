<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Locale\Exception;

class LocaleAlreadyExistsException extends \InvalidArgumentException
{
    public function __construct(string $message = 'The Locale already exists.')
    {
        parent::__construct($message);
    }
}
