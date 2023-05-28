<?php

declare(strict_types=1);

namespace Zentlix\User\Application\Locale\Command;

use Zentlix\Core\Application\Shared\Command\UpdateCommandInterface;
use Zentlix\User\Domain\Locale\DataTransferObject\Locale as LocaleDTO;

final class UpdateCommand implements UpdateCommandInterface
{
    public readonly LocaleDTO $data;

    public function __construct(LocaleDTO $locale)
    {
        $this->data = $locale;
    }
}
