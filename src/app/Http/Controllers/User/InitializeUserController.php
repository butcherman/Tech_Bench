<?php

namespace App\Http\Controllers\User;

use App\Actions\Fortify\ResetUserPassword;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\UserInitialize;
use App\Service\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class InitializeUserController extends Controller
{
    /**
     * Display the resource.
     */
    public function show(UserInitialize $token)
    {
        return Inertia::render('User/Initialize', [
            'token' => $token->token,
            'user' => $token->User,
            'rules' => Cache::PasswordRules(),
        ]);
    }

    /**
     * Set the users password and finish setting up their account
     */
    public function update(ResetPasswordRequest $request, UserInitialize $token)
    {
        $resetObj = new ResetUserPassword;
        $resetObj->reset($token->User, $request->only(['password', 'password_confirmation']));

        // Log the user in and send them to the Dashboard
        Auth::login($token->User, true);

        Log::stack(['daily', 'auth'])
            ->info('User '.$token->User->full_name.' has finished setting up their account');
        $token->delete();

        return redirect(route('dashboard'))->with('success', __('user.initialized'));
    }
}
