<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Service;

use Zentlix\User\Domain\Group\Exception\GroupNotFoundException;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;
use Zentlix\User\Domain\User\DataTransferObject\User;
use Zentlix\User\Domain\User\Exception\UserValidationException;

interface UserValidatorInterface
{
    /**
     * @throws UserValidationException
     * @throws GroupNotFoundException
     * @throws LocaleNotFoundException
     */
    public function preCreate(User $data): void;
}
