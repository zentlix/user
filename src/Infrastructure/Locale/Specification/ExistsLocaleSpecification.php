<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Locale\Specification;

use Ramsey\Uuid\UuidInterface;
use Spiral\Translator\TranslatorInterface;
use Zentlix\Core\Infrastructure\Shared\Specification\AbstractSpecification;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;
use Zentlix\User\Domain\Locale\ReadModel\Repository\CheckLocaleInterface;
use Zentlix\User\Domain\Locale\Specification\ExistsLocaleSpecificationInterface;

final class ExistsLocaleSpecification extends AbstractSpecification implements ExistsLocaleSpecificationInterface
{
    public function __construct(
        private readonly CheckLocaleInterface $checkLocale,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * @param UuidInterface|UuidInterface[] $uuid
     *
     * @throws LocaleNotFoundException
     */
    public function isExists(array|UuidInterface $uuid): bool
    {
        return $this->isSatisfiedBy($uuid);
    }

    /**
     * @param UuidInterface|UuidInterface[] $value
     *
     * @throws LocaleNotFoundException
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function isSatisfiedBy(mixed $value): bool
    {
        if ($value instanceof UuidInterface) {
            $value = [$value];
        }

        $exists = $this->checkLocale->exists($value);
        $locales = \array_map(static fn (UuidInterface $uuid) => $uuid->toString(), $exists);

        foreach ($value as $uuid) {
            if (!\in_array($uuid->toString(), $locales, true)) {
                throw new LocaleNotFoundException(
                    $this->translator->trans('user.locale.locale_is_not_exists', ['%uuid%' => $uuid->toString()])
                );
            }
        }

        return true;
    }
}
