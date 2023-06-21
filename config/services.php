<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    /**
     * Office 365/Microsoft Azure Login
     */
    'azure' => [
        'allow_login' => env('AZURE_ALLOW_LOGIN', false),
        'allow_register' => env('AZURE_ALLOW_REGISTER', false),
        'default_role_id' => env('AZURE_DEFAULT_ROLE_ID', 4),
        'client_id' => env('AZURE_CLIENT_ID'),
        'client_secret' => env('AZURE_CLIENT_SECRET'),
        'secret_expires' => env('AZURE_CLIENT_EXPIRES'),
        'redirect' => env('AZURE_REDIRECT_URI'),
        'tenant' => env('AZURE_TENANT_ID'),
    ],

];
