<?php

declare(strict_types=1);

use Cycle\Database\Config;

return [
    /**
     * Default database connection
     */
    'default' => 'default',

    /**
     * The Spiral/Database module provides support to manage multiple databases
     * in one application, use read/write connections and logically separate
     * multiple databases within one connection using prefixes.
     *
     * To register a new database simply add a new one into
     * "databases" section below.
     */
    'databases' => [
        'default' => [
            'driver' => 'postgres',
        ],
        'event_store' => [
            'driver' => 'postgres',
        ]
    ],

    /**
     * Each database instance must have an associated connection object.
     * Connections used to provide low-level functionality and wrap different
     * database drivers. To register a new connection you have to specify
     * the driver class and its connection options.
     */
    'drivers' => [
        'postgres' => new Config\PostgresDriverConfig(
            connection: new Config\Postgres\TcpConnectionConfig(
                database: env('DB_NAME'),
                host: env('DB_HOST'),
                port: env('DB_PORT'),
                user: env('DB_USER'),
                password: env('DB_PASSWORD'),
            ),
            queryCache: false,
            options: ['logQueryParameters' => true]
        ),
    ],
];
