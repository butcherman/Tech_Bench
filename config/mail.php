<?php

return [
    'driver' => 'smtp',
    'host'          => env('MAIL_HOST', 'smtp.example.com'),
    'port'          => env('MAIL_PORT', 587),
    'from' => [
        'address' => 'hello@example.com',
        'name' => 'Tech Bench',
    ],

    'encryption' => 'tls',
    'username'   => env('MAIL_USERNAME', 'login_username'),
    'password'   => env('MAIL_PASSWORD', 'login_password'),
    'sendmail'   => '/usr/sbin/sendmail -bs',
    'markdown'   => [
        'theme'  => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
