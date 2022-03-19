<?php

// use Illuminate\Support\Str;

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver'        => 'mysql',
            'url'            => env('DATABASE_URL'),
            'host'           => env('DB_HOST', 'database'),
            'port'           => env('DB_PORT', '3306'),
            'database'       => env('DB_DATABASE', 'tech-bench'),
            'username'       => env('DB_USERNAME', 'tbUser'),
            'password'       => env('DB_PASSWORD', 'tech_bench_database'),
            'unix_socket'    => env('DB_SOCKET', ''),
            'charset'        => 'utf8mb4',
            'collation'      => 'utf8mb4_unicode_ci',
            'prefix'         => '',
            'prefix_indexes' => true,
            'strict'         => true,
            'engine'         => null,
            'options'        => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
    ],
    'migrations' => 'migrations',
    'redis' => [
        'client'  => env('REDIS_CLIENT', 'predis'),
        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix'  => 'tech_bench_',
        ],
        'default' => [
            'url'      => env('REDIS_URL'),
            'host'     => env('REDIS_HOST', 'redis'),
            'password' => env('REDIS_PASSWORD', 'tech_bench_database'),
            'port'     => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],
        'cache' => [
            'url'      => env('REDIS_URL'),
            'host'     => env('REDIS_HOST', 'redis'),
            'password' => env('REDIS_PASSWORD', 'tech_bench_database'),
            'port'     => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],
    ],
];
