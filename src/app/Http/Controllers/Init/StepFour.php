<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StepFour extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Init/StepFour', [
            'step' => 4,
            'policy' => [
                'expire' => config('auth.passwords.settings.expire'),
                'min_length' => config('auth.passwords.settings.min_length'),
                'contains_uppercase' => (bool) config('auth.passwords.settings.contains_uppercase'),
                'contains_lowercase' => (bool) config('auth.passwords.settings.contains_lowercase'),
                'contains_number' => (bool) config('auth.passwords.settings.contains_number'),
                'contains_special' => (bool) config('auth.passwords.settings.contains_special'),
                'disable_compromised' => (bool) config('auth.passwords.settings.disable_compromised'),
            ],
        ]);
    }
}
