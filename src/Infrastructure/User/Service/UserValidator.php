<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\User\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zentlix\User\Domain\Group\Exception\GroupNotFoundException;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;
use Zentlix\User\Domain\Locale\Specification\ExistsLocaleSpecificationInterface;
use Zentlix\User\Domain\User\DataTransferObject\User as UserDTO;
use Zentlix\User\Domain\User\Exception\DuplicateEmailException;
use Zentlix\User\Domain\User\Exception\DuplicatePhoneException;
use Zentlix\User\Domain\User\Exception\UserValidationException;
use Zentlix\User\Domain\User\Exception\UserWithoutGroupException;
use Zentlix\User\Domain\User\Service\UserValidatorInterface;
use Zentlix\User\Domain\User\Specification\UniqueEmailSpecificationInterface;
use Zentlix\User\Domain\User\Specification\UniquePhoneSpecificationInterface;
use Zentlix\User\Domain\User\User;
use Zentlix\User\Infrastructure\Group\Specification\ExistsGroupSpecification;

readonly class UserValidator implements UserValidatorInterface
{
    public function __construct(
        protected ValidatorInterface $validator,
        protected UniqueEmailSpecificationInterface $uniqueEmailSpecification,
        protected UniquePhoneSpecificationInterface $uniquePhoneSpecification,
        protected ExistsGroupSpecification $existsGroupSpecification,
        protected ExistsLocaleSpecificationInterface $existsLocaleSpecification
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
    public function preCreate(UserDTO $data): void
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

    /**
     * @throws UserValidationException
     * @throws UserWithoutGroupException
     * @throws DuplicateEmailException
     * @throws DuplicatePhoneException
     * @throws GroupNotFoundException
     * @throws LocaleNotFoundException
     */
    public function preUpdate(UserDTO $data, User $user): void
    {
        $errors = $this->validator->validate($data);
        if ($errors->count() > 0) {
            throw new UserValidationException($errors);
        }
        if ([] === $data->getGroups()) {
            throw new UserWithoutGroupException('The user must have at least one group!');
        }

        if (!$data->getEmail()->isEqual($user->getEmail())) {
            $this->uniqueEmailSpecification->isUnique($data->getEmail());
        }

        if (
            null !== $data->getPhone() &&
            ($user->getPhone() === null || !$data->getPhone()->equals($user->getPhone()))
        ) {
            $this->uniquePhoneSpecification->isUnique($data->getPhone());
        }
        $this->existsGroupSpecification->isExists($data->getGroups());

        if (($uuid = $data->getLocale()) !== null) {
            $this->existsLocaleSpecification->isExists($uuid);
        }
    }
}
