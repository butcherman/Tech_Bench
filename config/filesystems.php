<?php

return [
    'default' => env('FILESYSTEM_DRIVER', 'local'),
    'cloud' => env('FILESYSTEM_CLOUD', 's3'),
    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => env('ROOT_FOLDER', storage_path('app')),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

    ],
    
    'paths' => [
        'default' => env('DFLT_FOLDER', DIRECTORY_SEPARATOR.'default'),
        'systems' => env('SYS_FOLDER', DIRECTORY_SEPARATOR.'systems'),
        'customers' => env('CUST_FOLDER', DIRECTORY_SEPARATOR.'customers'),
        'users' => env('USER_FOLDER', DIRECTORY_SEPARATOR.'users'),
        'tips' => env('TIP_FOLDER', DIRECTORY_SEPARATOR.'tips'),
        'links' => env('LINK_FOLDER', DIRECTORY_SEPARATOR.'links'),
        'company' => env('COMP_FOLDER', DIRECTORY_SEPARATOR.'company'),
        'max_size' => env('MAX_UPLOAD', ini_get('post_max_size')),
    ],

];
