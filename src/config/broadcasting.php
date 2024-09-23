<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    */

    'default' => env('BROADCAST_DRIVER', 'reverb'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    */

    'connections' => [
        'reverb' => [
            'driver' => 'reverb',
            'key' => env('REVERB_APP_KEY', 'app-key'),
            'secret' => env('REVERB_APP_SECRET', 'app-secret'),
            'app_id' => env('REVERB_APP_ID', 'app-id'),
            'options' => [
                'host' => 'reverb',
                'port' => 8080,
                'scheme' => 'http',
                'useTLS' => false,
            ],
            'client_options' => [
                // Guzzle client options: https://docs.guzzlephp.org/en/stable/request-options.html
            ],
        ],
    ],

];
