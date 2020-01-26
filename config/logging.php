<?php

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [
    'default'  => env('LOG_CHANNEL', 'daily'),
    'channels' => [
        'stack' => [
            'driver'            => 'stack',
            'channels'          => ['daily'],
            'ignore_exceptions' => false,
        ],
        'single' => [
            'driver'    => 'single',
            'path'      => storage_path('logs/TechBench.log'),
            'level'     => env('LOG_LEVEL', 'debug'),
        ],
        'daily' => [
            'driver'    => 'daily',
            'path'      => storage_path('logs/TechBench.log'),
            'level'     => env('LOG_LEVEL', 'debug'),
            'days'      => 14,
        ],
        'slack' => [
            'driver'    => 'slack',
            'url'       => env('LOG_SLACK_WEBHOOK_URL'),
            'username'  => 'Tech Bench Log',
            'emoji'     => ':boom:',
            'level'     => 'critical',
        ],
        'papertrail'        => [
            'driver'        => 'monolog',
            'level'         => env('LOG_LEVEL', 'debug'),
            'handler'       => SyslogUdpHandler::class,
            'handler_with'  => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
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
