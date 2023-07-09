<?php

declare(strict_types=1);

use Symfony\Component\Translation\Dumper\PhpFileDumper;
use Symfony\Component\Translation\Loader\PhpFileLoader;

return [
    'locale' => env('LOCALE', 'en'),
    'fallbackLocale' => env('LOCALE', 'en'),
    'directory' => directory('locale'),
    'directories' => [],
    'autoRegister' => env('DEBUG', true),
    'loaders' => [
        'php' => PhpFileLoader::class,
    ],
    'dumpers' => [
        'php' => PhpFileDumper::class,
    ],
    'domains' => [
        'messages' => ['*'],
    ],
];
