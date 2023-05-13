<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Service;

use Zentlix\User\Domain\Group\Exception\GroupNotFoundException;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;
use Zentlix\User\Domain\User\DataTransferObject\User;
use Zentlix\User\Domain\User\Exception\DuplicateEmailException;
use Zentlix\User\Domain\User\Exception\DuplicatePhoneException;
use Zentlix\User\Domain\User\Exception\UserValidationException;
use Zentlix\User\Domain\User\Exception\UserWithoutGroupException;

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
    public function preCreate(User $data): void;
}
