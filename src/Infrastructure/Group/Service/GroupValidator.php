<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\Service;

use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zentlix\User\Domain\Group\DataTransferObject\Group;
use Zentlix\User\Domain\Group\Exception\DuplicateCodeException;
use Zentlix\User\Domain\Group\Exception\GroupValidationException;
use Zentlix\User\Domain\Group\Service\GroupValidatorInterface;
use Zentlix\User\Domain\Group\Specification\UniqueCodeSpecificationInterface;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;
use Zentlix\User\Domain\Locale\Specification\ExistsLocaleSpecificationInterface;

class GroupValidator implements GroupValidatorInterface
{
    public function __construct(
        protected readonly ValidatorInterface $validator,
        protected readonly UniqueCodeSpecificationInterface $uniqueCodeSpecification,
        protected readonly ExistsLocaleSpecificationInterface $existsLocaleSpecification
    ) {
    }

    /**
     * @throws GroupValidationException
     * @throws DuplicateCodeException
     * @throws LocaleNotFoundException
     */
    public function preCreate(Group $data): void
    {
        $errors = $this->validator->validate($data);
        if ($errors->count() > 0) {
            throw new GroupValidationException($errors);
        }

        $this->uniqueCodeSpecification->isUnique($data->code);

        foreach ($data->getTitles() as $title) {
            $errors = $this->validator->validate($title);
            if ($errors->count() > 0) {
                throw new GroupValidationException($errors);
            }

            if (!$title->getGroup()->equals($data->uuid)) {
                throw new GroupValidationException(
                    ConstraintViolationList::createFromMessage('Invalid User group UUID.')
                );
            }

            $this->existsLocaleSpecification->isExists($title->getLocale());
        }
    }
}
