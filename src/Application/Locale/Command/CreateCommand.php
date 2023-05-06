<?php

declare(strict_types=1);

namespace Zentlix\User\Application\Locale\Command;

use Zentlix\Core\Application\Shared\Command\CreateCommandInterface;
use Zentlix\User\Domain\Locale\DataTransferObject\Locale;

final class CreateCommand implements CreateCommandInterface
{
    public readonly Locale $data;

    public function __construct()
    {
        $this->data = new Locale();
    }
}
