<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Locale\Event;

use Zentlix\User\Domain\Locale\DataTransferObject\Locale;

final class LocaleWasUpdated
{
    public function __construct(
        public readonly Locale $locale
    ) {
    }
}
