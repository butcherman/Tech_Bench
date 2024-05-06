<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),
    'max_filesize' => env('MAX_UPLOAD', 2000000000), //  2000000000 Bytes = 2GB
    'chunk_size' => 5000000,                       //  5000000 Bytes = 5 MB

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    */

    'disks' => [

        'local' => [
            'base_folder' => 'app',
            'driver' => 'local',
            'root' => storage_path('app'),
            'permissions' => [
                'file' => [
                    'public' => 0644,
                    'private' => 0644,
                ],
                'dir' => [
                    'public' => 0755,
                    'private' => 0755,
                ],
            ],
        ],

        /**
         * All Customer information is stored here
         */
        'customers' => [
            'driver' => 'local',
            'root' => storage_path('app/customers'),
        ],

        /**
         * All Tech Tip information is stored here
         */
        'tips' => [
            'driver' => 'local',
            'root' => storage_path('app/tips'),
        ],

        /**
         * File Links files are stored here
         */
        'fileLinks' => [
            'driver' => 'local',
            'root' => storage_path('app/file_links'),
        ],

        /**
         * Only used for public accessible items such as images
         */
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        /**
         * Application logs
         */
        'logs' => [
            'driver' => 'local',
            'root' => storage_path('logs'),
        ],

        /**
         * Application Backups
         */
        // 'backups' => [
        //     'driver' => 'local',
        //     'root' => storage_path('backups'),
        // ],

        /**
         * Security and SSL Certificates
         */
        'security' => [
            'driver' => 'local',
            'root' => base_path('keystore'),
        ],

        /**
         * Downloaded Update files will be stored here
         */
        // 'updates' => [
        //     'driver' => 'local',
        //     'root' => storage_path('updates'),
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
