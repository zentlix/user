<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\User\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zentlix\User\Domain\Group\Exception\GroupNotFoundException;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;
use Zentlix\User\Domain\Locale\Specification\ExistsLocaleSpecificationInterface;
use Zentlix\User\Domain\User\DataTransferObject\User;
use Zentlix\User\Domain\User\Exception\DuplicateEmailException;
use Zentlix\User\Domain\User\Exception\DuplicatePhoneException;
use Zentlix\User\Domain\User\Exception\UserValidationException;
use Zentlix\User\Domain\User\Exception\UserWithoutGroupException;
use Zentlix\User\Domain\User\Service\UserValidatorInterface;
use Zentlix\User\Domain\User\Specification\UniqueEmailSpecificationInterface;
use Zentlix\User\Domain\User\Specification\UniquePhoneSpecificationInterface;
use Zentlix\User\Infrastructure\Group\Specification\ExistsGroupSpecification;

class UserValidator implements UserValidatorInterface
{
    public function __construct(
        protected readonly ValidatorInterface $validator,
        protected readonly UniqueEmailSpecificationInterface $uniqueEmailSpecification,
        protected readonly UniquePhoneSpecificationInterface $uniquePhoneSpecification,
        protected readonly ExistsGroupSpecification $existsGroupSpecification,
        protected readonly ExistsLocaleSpecificationInterface $existsLocaleSpecification
    ) {
    }

    /**
     * @throws UserValidationException
     * @throws UserWithoutGroupException
     * @throws DuplicateEmailException
     * @throws DuplicatePhoneException
     * @throws GroupNotFoundException
     * @throws LocaleNotFoundException
     */
    public function preCreate(User $data): void
    {
        $errors = $this->validator->validate($data);
        if ($errors->count() > 0) {
            throw new UserValidationException($errors);
        }
        if ([] === $data->getGroups()) {
            throw new UserWithoutGroupException('The user must have at least one group!');
        }

        $this->uniqueEmailSpecification->isUnique($data->getEmail());
        if (null !== $data->getPhone()) {
            $this->uniquePhoneSpecification->isUnique($data->getPhone());
        }
        $this->existsGroupSpecification->isExists($data->getGroups());

        if (($uuid = $data->getLocale()) !== null) {
            $this->existsLocaleSpecification->isExists($uuid);
        }
    }
}
