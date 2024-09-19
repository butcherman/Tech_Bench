<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;

return [
    'default' => env('LOG_CHANNEL', 'daily'),
    'days' => 14,
    'log_level' => env('LOG_LEVEL', 'info'),
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],
        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/Application/TechBench.log'),
            'level' => env('LOG_LEVEL', 'info'),
            'permission' => 0777,
        ],
        // Default logging channel
        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/Application/TechBench.log'),
            'level' => env('LOG_LEVEL', 'info'),
            'days' => 14,
            'permission' => 0777,
        ],
        // All authentication - login/logout logging
        'auth' => [
            'driver' => 'daily',
            'path' => storage_path('logs/Auth/Auth.log'),
            'level' => env('LOG_LEVEL', 'info'),
            'days' => 14,
            'permission' => 0777,
        ],

        // 'stderr' => [
        //     'driver' => 'monolog',
        //     'handler' => StreamHandler::class,
        //     'formatter' => env('LOG_STDERR_FORMATTER'),
        //     'with' => [
        //         'stream' => 'php://stderr',
        //     ],
        // ],

        // 'syslog' => [
        //     'driver' => 'syslog',
        //     'level' => env('LOG_LEVEL', 'info'),
        // ],

        // 'errorlog' => [
        //     'driver' => 'errorlog',
        //     'level' => env('LOG_LEVEL', 'info'),
        // ],

        // 'null' => [
        //     'driver' => 'monolog',
        //     'handler' => NullHandler::class,
        // ],

        'emergency' => [
            'path' => storage_path('logs/Emergency/EmergencyLog.log'),
        ],
    ],

];
