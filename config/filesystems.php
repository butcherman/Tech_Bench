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
    'max_filesize' => env('MAX_UPLOAD', 1600),      //  1600 Megabytes = 2GB
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
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'customers' => [
            'driver' => 'local',
            'root'   => storage_path('app/customers'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],



    ],

    /*
    *   Path locations for uploaded files to the local disk
    */
    // 'paths' => [
    //     'default'    => env('DFLT_FOLDER', DIRECTORY_SEPARATOR.'default'),
    //     'customers'  => env('CUST_FOLDER', DIRECTORY_SEPARATOR.'customers'),
    //     'tips'       => env('TIP_FOLDER',  DIRECTORY_SEPARATOR.'tips'),
    // ],

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
