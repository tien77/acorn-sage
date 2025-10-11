<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'migrations' => env('DB_MIGRATIONS_TABLE', 'wp_acorn_migrations'),

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', defined('DB_HOST') ? DB_HOST : '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', defined('DB_NAME') ? DB_NAME : 'wordpress'),
            'username' => env('DB_USERNAME', defined('DB_USER') ? DB_USER : 'root'),
            'password' => env('DB_PASSWORD', defined('DB_PASSWORD') ? DB_PASSWORD : ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => env('DB_PREFIX', 'wp_'),
            'prefix_indexes' => true,
            'strict' => false,
        ],
    ],
];
