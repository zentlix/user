<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\Exception;

use Zentlix\Core\Domain\Shared\Exception\DomainException;

final class UserWithoutGroupException extends DomainException
{
}
