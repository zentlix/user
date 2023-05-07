<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\Service;

use Zentlix\User\Domain\Group\DataTransferObject\Group;
use Zentlix\User\Domain\Group\Exception\DuplicateCodeException;
use Zentlix\User\Domain\Group\Exception\GroupValidationException;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;

interface GroupValidatorInterface
{
    /**
     * @throws GroupValidationException
     * @throws DuplicateCodeException
     * @throws LocaleNotFoundException
     */
    public function preCreate(Group $data): void;
}
