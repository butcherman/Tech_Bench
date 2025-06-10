<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | which utilizes session storage plus the Eloquent user provider.
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | If you have multiple user tables or models you may configure multiple
    | providers to represent the model / table. These providers may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | These configuration options specify the behavior of Laravel's password
    | reset functionality, including the table utilized for token storage
    | and the user provider that is invoked to actually retrieve users.
    |
    | The expiry time is the number of minutes that each reset token will be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    | The throttle setting is the number of seconds a user must wait before
    | generating more password reset tokens. This prevents the user from
    | quickly generating a very large amount of password reset tokens.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'settings' => [
            'expire' => 30,
            'min_length' => 6,
            'contains_uppercase' => true,
            'contains_lowercase' => true,
            'contains_number' => true,
            'contains_special' => true,
            'disable_compromised' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | window expires and users are asked to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

    /*
    |--------------------------------------------------------------------------
    | Auto Logout Timer
    |--------------------------------------------------------------------------
    |
    | This option will define the amount of minutes that a user can site idle
    | before they are automatically logged out of the application
    |
    */

    'auto_logout_timer' => 15,

    /*
    |--------------------------------------------------------------------------
    | Login History Lifespan
    |--------------------------------------------------------------------------
    |
    | This option defines how long (in days) that user login history is kept
    |
    */

    'login_history_lifespan' => 730,

    /*
    |--------------------------------------------------------------------------
    | 2FA Authentication
    |--------------------------------------------------------------------------
    |
    | This option defines the settings for Two Factor Authentication.  Required
    | determines if this feature is enabled.  Allow save device allows the user
    | to list a devices as safe so that they will not be given the 2FA challenge
    | on their next visit with the same device.
    |
    */

    'twoFa' => [
        'required' => (bool) env('REQUIRE_2FA', false),
        'allow_save_device' => (bool) true,
        'allow_via_email' => (bool) true,
        'allow_via_authenticator' => (bool) true,
    ],

];
