<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;

return [
    'default' => env('LOG_CHANNEL', 'daily'),
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/TechBench.log'),
            'level' => env('LOG_LEVEL', 'info'),
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/TechBench.log'),
            'level' => env('LOG_LEVEL', 'info'),
            'days' => 14,
        ],



        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/TechBenchEmergency.log'),
        ],
    ],

];
