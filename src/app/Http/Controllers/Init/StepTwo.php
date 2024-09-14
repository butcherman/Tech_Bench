<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Service\TimezoneList;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StepTwo extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $settingsData = [
            'from_address' => config('mail.from.address'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => strtoupper(config('mail.mailers.smtp.encryption')),
            'username' => config('mail.mailers.smtp.username'),
            'password' => config('mail.mailers.smtp.password') ? __('admin.fake_password') : '',
            'require_auth' => (bool) config('mail.mailers.smtp.require_auth'),
        ];

        if ($request->session()->has('setup.email-settings')) {
            $settingsData = $request->session()->get('setup.email-settings');
        }

        return Inertia::render('Init/StepTwo', [
            'step' => 2,
            'settings' => $settingsData,
        ]);
    }
}
