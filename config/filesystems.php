<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default'      => env('FILESYSTEM_DRIVER', 'local'),
    'max_filesize' => env('MAX_UPLOAD', 2000000000), //  2000000000 Bytes = 2GB
    'chunk_size'   => 500000,                       //  500000 Bytes = 4 MB

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'base_folder' => 'app',
            'driver'      => 'local',
            'root'        => storage_path('app'),
        ],

        /**
         * All Customer information is stored here
         */
        'customers' => [
            'driver' => 'local',
            'root'   => storage_path('app/customers'),
        ],

        /**
         * All Tech Tip information is stored here
         */
        'tips' => [
            'driver' => 'local',
            'root'   => storage_path('app/tips'),
        ],

        /**
         * Only used for public accessible items such as images
         */
        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        /**
         * Application logs
         */
        'logs' => [
            'driver' => 'local',
            'root'   => storage_path('logs'),
        ],

        /**
         * Application Backups
         */
        'backups' => [
            'driver' => 'local',
            'root'   => storage_path('backups'),
        ],

        /**
         * All Tech Bench add on modules are stored here
         */
        'modules' => [
            'driver' => 'local',
            'root'   => base_path().'/Modules',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
