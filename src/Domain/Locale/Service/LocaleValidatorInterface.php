<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Locale\Service;

use Zentlix\User\Domain\Locale\Exception\DuplicateCodeException;
use Zentlix\User\Domain\Locale\Exception\LocaleValidationException;
use Zentlix\User\Domain\Locale\Locale;
use Zentlix\User\Domain\Locale\DataTransferObject\Locale as LocaleDTO;

interface LocaleValidatorInterface
{
    /**
     * @throws DuplicateCodeException
     * @throws LocaleValidationException
     */
    public function preCreate(LocaleDTO $data): void;

    public function preUpdate(LocaleDTO $data, Locale $locale): void;
}
