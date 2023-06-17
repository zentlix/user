<?php

declare(strict_types=1);

namespace Zentlix\User\Application\Locale\Command;

use Zentlix\Core\Application\Shared\Command\UpdateCommandInterface;
use Zentlix\User\Domain\Locale\DataTransferObject\Locale;

final class UpdateCommand implements UpdateCommandInterface
{
    public readonly Locale $data;

    public function __construct(Locale $locale)
    {
        $this->data = $locale;
    }
}
