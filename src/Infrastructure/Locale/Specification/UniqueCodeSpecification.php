<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Locale\Specification;

use Spiral\Translator\TranslatorInterface;
use Zentlix\Core\Infrastructure\Shared\Specification\AbstractSpecification;
use Zentlix\User\Domain\Locale\Exception\DuplicateCodeException;
use Zentlix\User\Domain\Locale\ReadModel\Repository\CheckLocaleByCodeInterface;
use Zentlix\User\Domain\Locale\Specification\UniqueCodeSpecificationInterface;

final class UniqueCodeSpecification extends AbstractSpecification implements UniqueCodeSpecificationInterface
{
    public function __construct(
        private readonly CheckLocaleByCodeInterface $checkLocaleByCode,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * @param non-empty-string $code
     *
     * @throws DuplicateCodeException
     */
    public function isUnique(string $code): bool
    {
        return $this->isSatisfiedBy($code);
    }

    /**
     * @param non-empty-string $value
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function isSatisfiedBy(mixed $value): bool
    {
        if ($this->checkLocaleByCode->existsCode($value)) {
            throw new DuplicateCodeException(
                $this->translator->trans('user.locale.code_already_exists', ['code' => $value])
            );
        }

        return true;
    }
}
