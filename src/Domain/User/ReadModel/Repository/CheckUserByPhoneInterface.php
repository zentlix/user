<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\ReadModel\Repository;

use libphonenumber\PhoneNumber;
use Ramsey\Uuid\UuidInterface;

interface CheckUserByPhoneInterface
{
    /**
     * @param PhoneNumber|PhoneNumber[] $phone
     *
     * @psalm-return ($phone is array ? array : ?UuidInterface)
     */
    public function existsPhone(PhoneNumber|array $phone): array|UuidInterface|null;
}
