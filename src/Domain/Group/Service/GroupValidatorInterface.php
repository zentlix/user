<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\Service;

use Zentlix\User\Domain\Group\DataTransferObject\Group;
use Zentlix\User\Domain\Group\Exception\DuplicateCodeException;
use Zentlix\User\Domain\Group\Exception\GroupValidationException;

interface GroupValidatorInterface
{
    /**
     * @throws GroupValidationException
     * @throws DuplicateCodeException
     */
    public function preCreate(Group $data): void;
}
