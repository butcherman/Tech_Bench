<?php

namespace App\Http\Controllers\User;

use App\Events\User\EmailChangedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserSettingsController extends Controller
{
    public function get(Request $request)
    {
        return Inertia::render('User/Settings', [
            'settings' => [],
        ]);
    }

    public function set(UserProfileRequest $request, User $user)
    {
        //  If the email address has changed, we will send a notification email to the old address
        if ($request->email !== $user->email) {
            event(new EmailChangedEvent($user->email, $user));
        }

        $user->update($request->toArray());
        Log::stack(['daily', 'user'])->info('User Information for '.$user->username.' has been updated by '.$request->user()->username, $request->toArray());

        return back()->with('success', __('user.updated'));
    }
}
