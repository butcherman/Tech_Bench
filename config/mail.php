<?php

return [
    'driver' => 'smtp',
    'host' => 'smtp.mailgun.org',
    'port' => 587,
    'from' => [
        'address' => 'hello@example.com',
        'name' => 'Example',
    ],

    'encryption' => 'tls',
    'username' => 'ExampleUsername',
    'password' => 'Password',
    'sendmail' => '/usr/sbin/sendmail -bs',
    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
