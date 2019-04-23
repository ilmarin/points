<?php

return [
    'default' => 'main',
    'migrations' => 'migrations',
    'connections' => [
        'main' => [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'port' => env('DB_PORT', '3306'),
            'charset' => 'utf8',
            'prefix' => '',
        ],
    ],
    'redis' => [
        'geocode-cache' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 1,
        ],
    ],
];

