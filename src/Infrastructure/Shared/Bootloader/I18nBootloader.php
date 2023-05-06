<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Shared\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Config\Patch\Append;
use Spiral\Translator\Catalogue\LoaderInterface;
use Spiral\Translator\Config\TranslatorConfig;
use Zentlix\User\Infrastructure\Shared\Translator\Catalogue\CatalogueLoader;

final class I18nBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        \Spiral\Bootloader\I18nBootloader::class
    ];

    protected const SINGLETONS = [
        LoaderInterface::class => CatalogueLoader::class
    ];

    public function __construct(
        private readonly ConfiguratorInterface $config
    ) {
    }

    public function init(DirectoriesInterface $dirs): void
    {
        $this->addDirectory(\rtrim($dirs->get('vendor'), '/') . '/zentlix/user/translations');
    }

    /**
     * @param non-empty-string $directory
     */
    public function addDirectory(string $directory): void
    {
        $this->config->modify(
            TranslatorConfig::CONFIG,
            new Append('directories', null, $directory)
        );
    }
}
