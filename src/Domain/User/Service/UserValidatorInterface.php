<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Service;

use Zentlix\User\Domain\Group\Exception\GroupNotFoundException;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;
use Zentlix\User\Domain\User\DataTransferObject\User as UserDTO;
use Zentlix\User\Domain\User\Exception\DuplicateEmailException;
use Zentlix\User\Domain\User\Exception\DuplicatePhoneException;
use Zentlix\User\Domain\User\Exception\UserValidationException;
use Zentlix\User\Domain\User\Exception\UserWithoutGroupException;
use Zentlix\User\Domain\User\User;

interface UserValidatorInterface
{
    /**
     * @throws UserValidationException
     * @throws UserWithoutGroupException
     * @throws DuplicateEmailException
     * @throws DuplicatePhoneException
     * @throws GroupNotFoundException
     * @throws LocaleNotFoundException
     */
    public function preCreate(UserDTO $data): void;

    /**
     * @throws UserValidationException
     * @throws UserWithoutGroupException
     * @throws DuplicateEmailException
     * @throws DuplicatePhoneException
     * @throws GroupNotFoundException
     * @throws LocaleNotFoundException
     */
    public function preUpdate(UserDTO $data, User $user): void;
}
