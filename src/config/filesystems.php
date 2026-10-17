<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    | Max File upload size and Chunk Size of chunked file uploads are also
    | defined here in bytes.  Default settings are 2GB for Max File upload
    | and 5MB for chunk upload size.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    'max_filesize' => env('MAX_UPLOAD', 2000000000),

    'chunk_size' => 5000000,

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'serve' => true,
            'throw' => false,
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
         * Used for public accessible items such as images
         */
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
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
        'backups' => [
            'driver' => 'local',
            'root' => storage_path('backups'),
        ],

        /**
         * Security and SSL Certificates
         */
        'security' => [
            'driver' => 'local',
            'root' => base_path('keystore'),
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
