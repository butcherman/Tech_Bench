<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication Settings
    |--------------------------------------------------------------------------
    */
    'twoFa' => [
        'required' => false,
        'allow_save_device' => true,
        'allow_via_email' => true,
        'allow_via_authenticator' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords and Password Settings
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'settings' => [
            'expire' => 30,         //  In days
            'min_length' => 6,      //  Minimum password length
            'contains_uppercase' => false, // true,
            'contains_lowercase' => false, // true,
            'contains_number' => false, // true,
            'contains_special' => false, // true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => 10800,
];
