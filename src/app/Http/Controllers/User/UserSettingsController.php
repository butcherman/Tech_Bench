<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserSettingsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $this->authorize('view', $request->user());

        return Inertia::render('User/Settings', [
            'allowSaveDevice' => config('auth.twoFa.allow_save_device'),
            'devices' => $request->user()->DeviceTokens,
            'settings' => [], //  $userSettings($request->user()),   //  BuildUserSettings::build($request->user()),
        ]);
    }
}
