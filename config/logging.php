<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [
    'default'  => env('LOG_CHANNEL', 'daily'),
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
            'permission' => 0777,
        ],
        //  Default logging channel
        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/TechBench.log'),
            'level' => env('LOG_LEVEL', 'info'),
            'days' => 14,
            'permission' => 0777,
        ],
        //  All User related logging
        'user' => [
            'driver' => 'daily',
            'path'   => storage_path('logs/Users/UserLog.log'),
            'level'  => env('LOG_LEVEL', 'info'),
            'days'   => 14,
            'permission' => 0777,
        ],
        //  All authentication - login/logout logging
        'auth' => [
            'driver' => 'daily',
            'path'   => storage_path('logs/Auth/AuthLog.log'),
            'level'  => env('LOG_LEVEL', 'info'),
            'days'   => 14,
            'permission' => 0777,
        ],
        //  All customer specific logging
        'cust' => [
            'driver' => 'daily',
            'path'   => storage_path('logs/Cust/CustLog.log'),
            'level'  => env('LOG_LEVEL', 'info'),
            'days'   => 14,
            'permission' => 0777,
        ],
        //  All Tech Tip specific logging
        'tip' => [
            'driver' => 'daily',
            'path'   => storage_path('logs/TechTip/TechTipLog.log'),
            'level'  => env('LOG_LEVEL', 'info'),
            'days'   => 14,
            'permission' => 0777,
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
            'path' => storage_path('logs/EmergencyLog.log'),
        ],
    ],

];
