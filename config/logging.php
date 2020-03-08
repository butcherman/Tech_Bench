<?php

use Monolog\Handler\StreamHandler;

return [
    'default'  => env('LOG_CHANNEL', 'daily'),
    'channels' => [
        'stack' => [
            'driver'            => 'stack',
            'channels'          => ['daily'],
            'ignore_exceptions' => false,
        ],
        'single' => [
            'driver'     => 'single',
            'path'       => storage_path('logs/TechBench.log'),
            'level'      => env('LOG_LEVEL', 'debug'),
            'permission' => 0644
        ],
        'daily' => [
            'driver'     => 'daily',
            'path'       => storage_path('logs/TechBench.log'),
            'level'      => env('LOG_LEVEL', 'debug'),
            'days'       => 14,
            'permission' => 0664
        ],
        'stderr' => [
            'driver'    => 'monolog',
            'handler'   => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with'      => [
                'stream' => 'php://stderr',
            ],
        ],
        'syslog' => [
            'driver' => 'syslog',
            'level'  => env('LOG_LEVEL', 'debug'),
        ],
        'errorlog' => [
            'driver' => 'errorlog',
            'level'  => env('LOG_LEVEL', 'debug'),
        ],
    ],
];
