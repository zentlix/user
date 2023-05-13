<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\ReadModel\Repository;

use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\User\ValueObject\Email;

interface CheckUserByEmailInterface
{
    /**
     * @param Email|Email[] $email
     *
     * @psalm-return ($email is array ? array : ?UuidInterface)
     */
    public function existsEmail(Email|array $email): array|UuidInterface|null;
}
