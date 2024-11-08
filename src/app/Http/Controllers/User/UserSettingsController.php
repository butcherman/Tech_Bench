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
            'allowSaveDevice' => fn () => config('auth.twoFa.allow_save_device'),
            'devices' => fn () => $request->user()->DeviceTokens,
            'settings' => fn () => $svc($request->user()),
        ]);
    }
}
