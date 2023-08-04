<?php

namespace App\Http\Controllers\Admin\User;

use App\Actions\BuildAdminUserSettings;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserSettingsRequest;
use App\Models\User;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/**
 * View and modify Global User Settings (login settings)
 */
class UserSettingsController extends Controller
{
    use AppSettingsTrait;

    public function get()
    {
        $this->authorize('manage', User::class);

        $settingsObj = new BuildAdminUserSettings;

        return Inertia::render('Admin/User/Settings', [
            'two-fa' => $settingsObj->buildTwoFaSettings(),
            'oath' => $settingsObj->buildOathSettings(),
            'twilio' => $settingsObj->buildTwilioSettings(),
        ]);
    }

    public function set(UserSettingsRequest $request)
    {
        $this->saveSettingsArray($request->oath, 'services.azure');
        $this->saveSettingsArray($request->twoFa, 'auth.twoFa');
        $this->saveSettingsArray($request->twilio, 'services.twilio');

        Log::notice('User Settings updated by '.$request->user()->username, $request->except('client_secret'));

        //  If the user just enabled 2FA, they will be prompted for a code immediately.
        //  Bypass by manually adding verification to session
        $request->session()->put('2fa_verified', true);

        return back()->with('success', __('admin.user.settings_updated'));
    }
}
