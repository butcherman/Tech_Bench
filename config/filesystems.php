<?php

return [
    'default' => env('FILESYSTEM_DRIVER', 'local'),
    'cloud'   => env('FILESYSTEM_CLOUD', 's3'),
    'disks'   => [
        'local' => [
            'driver' => 'local',
            'root'   => env('ROOT_FOLDER', storage_path('app'.DIRECTORY_SEPARATOR.'files')),
        ],
        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app'.DIRECTORY_SEPARATOR.'public'),
            'url'        => env('APP_URL').DIRECTORY_SEPARATOR.'/storage',
            'visibility' => 'public',
        ],
        'backup' => [
            'driver' => 'local',
            'root'   => env('BACKUP_FOLDER', storage_path('backups')),
        ],
        'logs' => [
            'driver' => 'local',
            'root'   => storage_path('logs'),
        ],
        'snapshots' => [
            'driver' => 'local',
            'root'   => database_path('snapshots'),
        ],
    ],
    'paths' => [
        'default'   => env('DFLT_FOLDER', DIRECTORY_SEPARATOR.'default'),
        'systems'   => env('SYS_FOLDER', DIRECTORY_SEPARATOR.'systems'),
        'customers' => env('CUST_FOLDER', DIRECTORY_SEPARATOR.'customers'),
        'users'     => env('USER_FOLDER', DIRECTORY_SEPARATOR.'users'),
        'tips'      => env('TIP_FOLDER', DIRECTORY_SEPARATOR.'tips'),
        'links'     => env('LINK_FOLDER', DIRECTORY_SEPARATOR.'links'),
        'company'   => env('COMP_FOLDER', DIRECTORY_SEPARATOR.'company'),
        'max_size'  => env('MAX_UPLOAD', 1073741824),
    ],
];
