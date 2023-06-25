<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\User\Specification;

use Spiral\Translator\TranslatorInterface;
use Zentlix\Core\Infrastructure\Shared\Specification\AbstractSpecification;
use Zentlix\User\Domain\User\Exception\DuplicateEmailException;
use Zentlix\User\Domain\User\ReadModel\Repository\CheckUserByEmailInterface;
use Zentlix\User\Domain\User\Specification\UniqueEmailSpecificationInterface;
use Zentlix\User\Domain\User\ValueObject\Email;

final class UniqueEmailSpecification extends AbstractSpecification implements UniqueEmailSpecificationInterface
{
    public function __construct(
        private readonly CheckUserByEmailInterface $checkUserByEmail,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * @throws DuplicateEmailException
     */
    public function isUnique(Email $email): bool
    {
        return $this->isSatisfiedBy($email);
    }

    /**
     * @param Email $value
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function isSatisfiedBy($value): bool
    {
        if ($this->checkUserByEmail->existsEmail($value)) {
            throw new DuplicateEmailException(
                $this->translator->trans('user.user.email.already_exists', ['email' => $value->getValue()])
            );
        }

        return true;
    }
}
