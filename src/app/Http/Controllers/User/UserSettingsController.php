<?php

namespace App\Http\Controllers\User;

use App\Actions\User\BuildUserSettings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserSettingsController extends Controller
{
    /**
     * Show the Users Account and Application Settings
     */
    public function __invoke(Request $request, BuildUserSettings $svc): Response
    {
        $this->authorize('view', $request->user());

        return Inertia::render('User/Settings', [
            'settings' => fn() => $svc($request->user()),
            'two-fa' => [
                'allowSaveDevice' => fn() => config('auth.twoFa.allow_save_device')
                    && config('auth.twoFa.required'),
                'allowAuthenticator' => fn() => config('auth.twoFa.allow_via_authenticator')
                    && config('auth.twoFa.required'),
                'allowEmail' => fn() => config('auth.twoFa.allow_via_email')
                    && config('auth.twoFa.required'),
                'currentVia' => fn() => $request->user()->two_factor_via,
                'devices' => fn() => $request->user()->DeviceTokens,
                'enabled' => fn() => config('auth.twoFa.required'),
            ],
        ]);
    }
}
