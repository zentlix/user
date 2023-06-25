<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\Service;

use Zentlix\Core\Domain\Shared\Exception\DomainException;
use Zentlix\User\Domain\Group\DataTransferObject\Group as GroupDTO;
use Zentlix\User\Domain\Group\Exception\DuplicateCodeException;
use Zentlix\User\Domain\Group\Exception\GroupValidationException;
use Zentlix\User\Domain\Group\Group;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;

interface GroupValidatorInterface
{
    /**
     * @throws GroupValidationException
     * @throws DuplicateCodeException
     * @throws LocaleNotFoundException
     */
    public function preCreate(GroupDTO $data): void;

    /**
     * @throws GroupValidationException
     * @throws DuplicateCodeException
     * @throws LocaleNotFoundException
     * @throws DomainException
     */
    public function preUpdate(GroupDTO $data, Group $group): void;
}
