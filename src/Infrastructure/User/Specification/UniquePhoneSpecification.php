<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\User\Specification;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Spiral\Translator\TranslatorInterface;
use Zentlix\Core\Infrastructure\Shared\Specification\AbstractSpecification;
use Zentlix\User\Domain\User\Exception\DuplicatePhoneException;
use Zentlix\User\Domain\User\ReadModel\Repository\CheckUserByPhoneInterface;
use Zentlix\User\Domain\User\Specification\UniquePhoneSpecificationInterface;

final class UniquePhoneSpecification extends AbstractSpecification implements UniquePhoneSpecificationInterface
{
    public function __construct(
        private readonly CheckUserByPhoneInterface $checkUserByPhone,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * @throws DuplicatePhoneException
     */
    public function isUnique(PhoneNumber $phone): bool
    {
        return $this->isSatisfiedBy($phone);
    }

    /**
     * @param PhoneNumber $value
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function isSatisfiedBy($value): bool
    {
        if ($this->checkUserByPhone->existsPhone($value)) {
            throw new DuplicatePhoneException($this->translator->trans(
                'user.user.phone.already_exists',
                ['%phone%' => PhoneNumberUtil::getInstance()->format($value, PhoneNumberFormat::E164)]
            ));
        }

        return true;
    }
}
