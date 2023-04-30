<?php

namespace App\Http\Controllers\Init;

use Inertia\Inertia;
use App\Http\Controllers\Controller;

class StepFour extends Controller
{
    /**
     * User Password and Login Policy for first time setup
     */
    public function __invoke()
    {
        return Inertia::render('Init/StepFour', [
            'policy' => [
                'expire' => config('auth.passwords.settings.expire'),
                'min_length' => config('auth.passwords.settings.min_length'),
                'contains_uppercase' => (bool) config('auth.passwords.settings.contains_uppercase'),
                'contains_lowercase' => (bool) config('auth.passwords.settings.contains_lowercase'),
                'contains_number' => (bool) config('auth.passwords.settings.contains_number'),
                'contains_special' => (bool) config('auth.passwords.settings.contains_special'),
                'allowOath'     => (bool) config('services.azure.allow_login'),
                'allowRegister' => (bool) config('services.azure.allow_register'),
                'tenantId'      => config('services.azure.tenant'),
                'clientId'      => config('services.azure.client_id'),
                'clientSecret'  => config('services.azure.client_secret') ? __('admin.fake_password') : '',
                'redirectUri'   => config('app.url').'/auth/callback',
            ],
        ]);
    }
}
