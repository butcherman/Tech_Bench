<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StepThree extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $settingsData = [
            'expire' => config('auth.passwords.settings.expire'),
            'min_length' => config('auth.passwords.settings.min_length'),
            'contains_uppercase' => (bool) config('auth.passwords.settings.contains_uppercase'),
            'contains_lowercase' => (bool) config('auth.passwords.settings.contains_lowercase'),
            'contains_number' => (bool) config('auth.passwords.settings.contains_number'),
            'contains_special' => (bool) config('auth.passwords.settings.contains_special'),
            'disable_compromised' => (bool) config('auth.passwords.settings.disable_compromised'),
        ];

        if ($request->session()->has('setup.user-settings')) {
            $settingsData = $request->session()->get('setup.user-settings');
        }

        return Inertia::render('Init/StepThree', [
            'step' => 3,
            'policy' => $settingsData,
        ]);
    }
}
