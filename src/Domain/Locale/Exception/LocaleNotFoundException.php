<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Locale\Exception;

use Zentlix\Core\Domain\Shared\Exception\NotFoundException;

class LocaleNotFoundException extends NotFoundException
{
    public function __construct(string $message = 'The Locale does not exist.')
    {
        parent::__construct($message);
    }
}
