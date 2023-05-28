<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Translator;

use Spiral\Translator\CatalogueManagerInterface;
use Spiral\Translator\Translator as SpiralTranslator;
use Zentlix\User\Domain\Locale\ReadModel\LocaleView;
use Zentlix\User\Domain\Locale\ReadModel\Repository\LocaleRepositoryInterface;
use Zentlix\User\Domain\Translator\TranslatorInterface;

final class Translator implements TranslatorInterface
{
    private LocaleView $localeView;

    public function __construct(
        private readonly SpiralTranslator $translator,
        private readonly LocaleRepositoryInterface $localeRepository
    ) {
    }

    public function getLocale(): string
    {
        return $this->translator->getLocale();
    }

    public function getDomain(string $bundle): string
    {
        return $this->translator->getDomain($bundle);
    }

    public function getCatalogueManager(): CatalogueManagerInterface
    {
        return $this->translator->getCatalogueManager();
    }

    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @return LocaleView[]
     */
    public function getAvailableLocales(): array
    {
        return $this->localeRepository->findAll();
    }

    public function getLocaleView(): LocaleView
    {
        return $this->localeView;
    }

    public function setLocale(string $locale): void
    {
        $this->translator->setLocale($locale);
    }

    public function setLocaleView(LocaleView $localeView): void
    {
        $this->localeView = $localeView;
    }
}
