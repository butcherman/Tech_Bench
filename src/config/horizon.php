<?php

return [
    'domain' => null,
    'path' => 'administration/horizon',
    'use' => 'default',
    'prefix' => 'tech_bench_horizon:',
    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Queue Wait Time Thresholds
    |--------------------------------------------------------------------------
    */

    'waits' => [
        'redis:default' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Job Trimming Times
    |--------------------------------------------------------------------------
    */

    'trim' => [
        'recent' => 60,
        'pending' => 60,
        'completed' => 60,
        'recent_failed' => 10080,
        'failed' => 10080,
        'monitored' => 10080,
    ],

    /*
    |--------------------------------------------------------------------------
    | Metrics
    |--------------------------------------------------------------------------
    */

    'metrics' => [
        'trim_snapshots' => [
            'job' => 24,
            'queue' => 24,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fast Termination
    |--------------------------------------------------------------------------
    */

    'fast_termination' => false,

    /*
    |--------------------------------------------------------------------------
    | Memory Limit (MB)
    |--------------------------------------------------------------------------
    */

    'memory_limit' => 64,

    /*
    |--------------------------------------------------------------------------
    | Queue Worker Configuration
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        // 'supervisor-1' => [
        //     'connection' => 'redis',
        //     'queue' => ['default'],
        //     'balance' => 'auto',
        //     'maxProcesses' => 10,
        //     'maxTime' => 0,
        //     'maxJobs' => 0,
        //     'memory' => 128,
        //     'tries' => 1,
        //     'timeout' => 60,
        //     'nice' => 0,
        // ],
        'job-queue' => [
            'connection' => 'redis',
            'queue' => ['default'],
            'balance' => 'auto',
            'maxProcesses' => 10,
            'maxTime' => 0,
            'maxjobs' => 0,
            'memory' => 128,
            'tried' => 1,
            'timeout' => 60,
            'nice' => 0,
        ],
        'job-queue' => [
            'connection' => 'redis',
            'queue' => ['default'],
            'balance' => 'auto',
            'maxProcesses' => 10,
            'balanceMaxShift' => 1,
            'balanceCooldown' => 3,
            'maxTime' => 0,
            'maxjobs' => 0,
            'memory' => 128,
            'tried' => 1,
            'timeout' => 60,
            'nice' => 0,
        ],
        'mail-queue' => [
            'connection' => 'redis',
            'queue' => ['mail'],
            'balance' => 'auto',
            'maxProcesses' => 10,
            'balanceMaxShift' => 1,
            'balanceCooldown' => 3,
            'maxTime' => 0,
            'maxjobs' => 0,
            'memory' => 128,
            'tried' => 1,
            'timeout' => 60,
            'nice' => 0,
        ],
    ],

    // 'environments' => [
    //     'production' => [
    //         'supervisor-1' => [
    //             'maxProcesses' => 10,
    //             'balanceMaxShift' => 1,
    //             'balanceCooldown' => 3,
    //         ],
    //     ],

    //     'local' => [
    //         'supervisor-1' => [
    //             'maxProcesses' => 5,
    //         ],
    //     ],
    // ],
];