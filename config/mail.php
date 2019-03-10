<?php

return [
    'driver'        => 'smtp',
    'host'          => 'smtp.example.com',
    'port'          => 587,

    'from'          => [
        'address'       => 'no-reply@example.com',
        'name'          => 'Tech Bench',
    ],
    'encryption'    => 'tls',
    'username'      => 'MAIL_USERNAME',
    'password'      => 'MAIL_PASSWORD',
    'sendmail'      => '/usr/sbin/sendmail -bs',
    'markdown'      => [
        'theme'         => 'default',
        'paths'         => [
            resource_path('views/vendor/mail'),
        ],
    ],
    'log_channel'   => env('MAIL_LOG_CHANNEL'),
];
