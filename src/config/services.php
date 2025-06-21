<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    /**
     * Office 365/Microsoft Azure Login
     */
    'azure' => [
        'allow_login' => env('AZURE_ALLOW_LOGIN', false),
        'allow_register' => env('AZURE_ALLOW_REGISTER', false),
        'default_role_id' => 4,
        'allow_bypass_2fa' => env('AZURE_BYPASS_2FA', false),
        'default_role_id' => env('AZURE_DEFAULT_ROLE_ID', 4),
        'client_id' => env('AZURE_CLIENT_ID'),
        'client_secret' => env('AZURE_CLIENT_SECRET'),
        'secret_expires' => env('AZURE_CLIENT_EXPIRES'),
        'redirect' => env('AZURE_REDIRECT_URI'),
        'tenant' => env('AZURE_TENANT_ID'),
    ],

];
