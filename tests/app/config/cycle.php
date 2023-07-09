<?php

declare(strict_types=1);

use Cycle\ORM\Collection\DoctrineCollectionFactory;
use Cycle\ORM\Parser\Typecast;
use Cycle\ORM\SchemaInterface;
use Zentlix\Core\Infrastructure\Shared\Cycle\ExtendedTypecast;

return [
    'schema' => [
        'cache' => env('CYCLE_SCHEMA_CACHE', true),
        'collections' => [
            'default' => 'doctrine',
            'factories' => ['doctrine' => new DoctrineCollectionFactory()],
        ],
        'defaults' => [
            SchemaInterface::TYPECAST_HANDLER => [
                Typecast::class,
                ExtendedTypecast::class
            ],
        ],
    ],
    'warmup' => env('CYCLE_SCHEMA_WARMUP', false),
];
