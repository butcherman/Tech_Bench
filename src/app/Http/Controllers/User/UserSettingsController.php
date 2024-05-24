<?php

namespace App\Http\Controllers\User;

use App\Actions\BuildUserSettings;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserAccountRequest;
use App\Http\Requests\User\UserSettingsRequest;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserSettingsController extends Controller
{
    /**
     * Show the users Settings and Account Information
     */
    public function show(Request $request)
    {
        $this->authorize('view', $request->user());

        return Inertia::render('User/Settings', [
            'twoFaEnabled' => config('auth.twoFa.required')
                && config('auth.twoFa.allow_save_device'),
            'devices' => DeviceToken::where('user_id', $request->user()->user_id)->get(),
            'settings' => BuildUserSettings::build($request->user()),
        ]);
    }

    /**
     * Modify the Users Account settings
     */
    public function store(UserAccountRequest $request, User $user)
    {
        $request->checkForEmailChange();
        $user->update($request->only(['first_name', 'last_name', 'email']));

        Log::channel('user')->info('User Information for ' . $user->username .
            ' has been updated by ' . $request->user()->username, $request->toArray());

        return back()->with('success', __('user.updated'));
    }

    /**
     * Update the Users Settings
     */
    public function update(UserSettingsRequest $request, User $user)
    {
        $request->updateSettings();

        Log::channel('user')->info('User Settings for ' . $user->username .
            ' have been updated by ' . $request->user()->username, $user->toArray());

        return back()->with('success', __('user.updated'));
    }
}
