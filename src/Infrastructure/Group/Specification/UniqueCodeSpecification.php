<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\Specification;

use Spiral\Translator\TranslatorInterface;
use Zentlix\Core\Infrastructure\Shared\Specification\AbstractSpecification;
use Zentlix\User\Domain\Group\Exception\DuplicateCodeException;
use Zentlix\User\Domain\Group\Specification\UniqueCodeSpecificationInterface;
use Zentlix\User\Domain\Group\ReadModel\Repository\CheckGroupByCodeInterface;

final class UniqueCodeSpecification extends AbstractSpecification implements UniqueCodeSpecificationInterface
{
    public function __construct(
        private readonly CheckGroupByCodeInterface $checkGroupByCode,
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
    public function isSatisfiedBy($value): bool
    {
        if ($this->checkGroupByCode->existsCode($value)) {
            throw new DuplicateCodeException(
                $this->translator->trans('user.group.code_already_exists', ['%code%' => $value])
            );
        }

        return true;
    }
}
