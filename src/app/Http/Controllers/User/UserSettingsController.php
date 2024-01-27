<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserAccountRequest;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserSettingsController extends Controller
{
    /**
     * Display the resource.
     */
    public function show(Request $request)
    {
        return Inertia::render('User/Settings', [
            'twoFaEnabled' => config('auth.twoFa.required')
                && config('auth.twoFa.allow_save_device'),
            'devices' => DeviceToken::where('user_id', $request->user()->user_id)->get(),
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(UserAccountRequest $request, User $user)
    {
        $request->checkForEmailChange();
        $user->update($request->only(['first_name', 'last_name', 'email']));

        Log::channel('user')->info('User Information for '.$user->username.
            ' has been updated by '.$request->user()->username, $request->toArray());

        return back()->with('success', __('user.updated'));
    }
}
