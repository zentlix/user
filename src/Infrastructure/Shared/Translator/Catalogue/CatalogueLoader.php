<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Shared\Translator\Catalogue;

use Spiral\Logger\Traits\LoggerTrait;
use Spiral\Translator\Catalogue;
use Spiral\Translator\Catalogue\LoaderInterface;
use Spiral\Translator\CatalogueInterface;
use Spiral\Translator\Config\TranslatorConfig;
use Symfony\Component\Finder\Finder;

final class CatalogueLoader implements LoaderInterface
{
    use LoggerTrait;

    public function __construct(
        private readonly TranslatorConfig $config
    ) {
    }

    public function hasLocale(string $locale): bool
    {
        $locale = \preg_replace('/[^a-zA-Z_]/', '', \mb_strtolower($locale));

        return \is_dir($this->config->getLocaleDirectory($locale));
    }

    public function getLocales(): array
    {
        /** @var array<array-key, non-empty-string> $directories */
        $directories = $this->config->toArray()['directories'] ?? [];
        if (\is_dir($this->config->getLocalesDirectory())) {
            $directories[] = $this->config->getLocalesDirectory();
        }

        if ($directories === []) {
            return [];
        }

        $finder = new Finder();
        $locales = [];
        foreach ($finder->in($directories)->directories() as $directory) {
            $locales[] = $directory->getFilename();
        }

        return \array_unique($locales);
    }

    public function loadCatalogue(string $locale): CatalogueInterface
    {
        $locale = \preg_replace('/[^a-zA-Z_]/', '', \mb_strtolower($locale));
        $catalogue = new Catalogue($locale);

        if (!$this->hasLocale($locale)) {
            return $catalogue;
        }

        $directories = [];
        /** @var non-empty-string $directory */
        foreach ($this->config->toArray()['directories'] ?? [] as $directory) {
            if (\is_dir(\rtrim($directory, '/') . '/' . $locale)) {
                $directories[] = \rtrim($directory, '/') . '/' . $locale;
            }
        }
        if (\is_dir($this->config->getLocalesDirectory())) {
            $directories[] = $this->config->getLocalesDirectory() . $locale;
        }

        $finder = new Finder();
        foreach ($finder->in($directories)->files() as $file) {
            $this->getLogger()->info(
                \sprintf(
                    "found locale domain file '%s'",
                    $file->getFilename()
                ),
                ['file' => $file->getFilename()]
            );

            //Per application agreement domain name must present in filename
            $domain = \strstr($file->getFilename(), '.', true);

            if (!$this->config->hasLoader($file->getExtension())) {
                $this->getLogger()->warning(
                    \sprintf(
                        "unable to load domain file '%s', no loader found",
                        $file->getFilename()
                    ),
                    ['file' => $file->getFilename()]
                );

                continue;
            }

            $catalogue->mergeFrom(
                $this->config->getLoader($file->getExtension())->load(
                    (string)$file,
                    $locale,
                    $domain
                ),
                false
            );
        }

        return $catalogue;
    }
}
