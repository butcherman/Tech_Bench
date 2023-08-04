<?php

namespace App\Http\Controllers\User;

use App\Actions\BuildPasswordRules;
use App\Events\User\PasswordChangedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\InitializeUserRequest;
use App\Models\UserInitialize;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/**
 * Show and save form for a user to finish setting up their own account
 */
class InitializeUserController extends Controller
{
    public function get(UserInitialize $token)
    {
        return Inertia::render('User/Initialize', [
            'link' => $token->token,
            'user' => $token->User,
            'password-rules' => Cache::get('passwordRules', (new BuildPasswordRules)->build()),
        ]);
    }

    public function set(InitializeUserRequest $request, UserInitialize $token)
    {
        $user = $token->User;
        $user->forceFill([
            'password' => Hash::make($request->password),
            'password_expires' => $user->getNewExpireTime(),
        ])->save();

        Log::stack(['daily', 'auth', 'user'])->info('User '.$user->username.' has finished setting up their account');
        event(new PasswordChangedEvent($user));

        // Remove the token to invalidate it
        $token->delete();

        return redirect(route('login'))->with('success', __('user.initialized'));
    }
}
