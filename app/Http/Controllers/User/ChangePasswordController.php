<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use Inertia\Inertia;
use App\Actions\BuildPasswordRules;

class ChangePasswordController extends Controller
{
    /**
     * Change password page
     */
    public function get()
    {
        return Inertia::render('User/ChangePassword', [
            'password_rules' => (new BuildPasswordRules)->build(),
        ]);
    }

    /**
     * Change the password for the currently logged in user
     */
    public function set(ChangePasswordRequest $request)
    {
        $user = User::find($request->user()->user_id);
        $user->update([
            'password'         => Hash::make($request->password),
            'password_expires' => $user->getNewExpireTime(),
        ]);

        Log::channel('user')->info('User '.$request->user()->username.' has updated their password');
        return redirect(route('dashboard'))->with('success', __('user.password_changed'));
    }
}
