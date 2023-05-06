<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Locale\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zentlix\User\Domain\Locale\DataTransferObject\Locale as LocaleDTO;
use Zentlix\User\Domain\Locale\Exception\DuplicateCodeException;
use Zentlix\User\Domain\Locale\Exception\LocaleValidationException;
use Zentlix\User\Domain\Locale\Locale;
use Zentlix\User\Domain\Locale\Service\LocaleValidatorInterface;
use Zentlix\User\Domain\Locale\Specification\UniqueCodeSpecificationInterface;

class LocaleValidator implements LocaleValidatorInterface
{
    public function __construct(
        protected readonly ValidatorInterface $validator,
        protected readonly UniqueCodeSpecificationInterface $uniqueCodeSpecification
    ) {
    }

    /**
     * @throws LocaleValidationException
     * @throws DuplicateCodeException
     */
    public function preCreate(LocaleDTO $data): void
    {
        $errors = $this->validator->validate($data);
        if ($errors->count() > 0) {
            throw new LocaleValidationException($errors);
        }

        $this->uniqueCodeSpecification->isUnique($data->getCode());
    }

    public function preUpdate(LocaleDTO $data, Locale $locale): void
    {
        $errors = $this->validator->validate($data);
        if ($errors->count() > 0) {
            throw new LocaleValidationException($errors);
        }

        if ($locale->getCode() !== $data->getCode()) {
            $this->uniqueCodeSpecification->isUnique($data->getCode());
        }
    }
}
