<?php

namespace App\Http\Controllers\User;

use App\Actions\BuildPasswordRules;
use App\Events\User\PasswordChangedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\InitializeUserRequest;
use App\Models\UserInitialize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class InitializeUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
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

        $token->delete();

        return redirect(route('login'))->with('success', __('user.initialized'));
    }
}
