<?php

namespace App\Http\Controllers\User;

use App\Actions\Fortify\ResetUserPassword;
use App\Events\User\UserInitializeComplete;
use App\Facades\CacheData;
use App\Facades\CacheFacade;
use App\Http\Controllers\Controller;
use App\Models\UserInitialize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InitializeUserController extends Controller
{
    public function __construct(protected ResetUserPassword $svc) {}

    /**
     * Show the finish user profile setup page.
     */
    public function show(UserInitialize $token): Response
    {
        return Inertia::render('User/Initialize', [
            'token' => fn() => $token->token,
            'user' => fn() => $token->User,
            'rules' => fn() => CacheData::passwordRules(),
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request, UserInitialize $token): RedirectResponse
    {
        $this->svc->reset(
            $token->User,
            $request->only(['password', 'password_confirmation'])
        );

        event(new UserInitializeComplete($token));

        return redirect(route('login'))->with('success', __('user.initialized'));
    }
}
