<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Translator;

use Spiral\Translator\TranslatorInterface as SpiralTranslator;
use Zentlix\User\Domain\Locale\ReadModel\LocaleView;

interface TranslatorInterface extends SpiralTranslator
{
    /**
     * @return LocaleView[]
     */
    public function getAvailableLocales(): array;

    public function getLocaleView(): LocaleView;
}
