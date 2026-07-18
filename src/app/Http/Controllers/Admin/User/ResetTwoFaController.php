<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserAdministrationService;
use Illuminate\Http\RedirectResponse;

class ResetTwoFaController extends Controller
{
    public function __construct()
    {
        if (! config('auth.twoFa.required')) {
            abort(403);
        }
    }

    /**
     * Reset a users 2FA Settings
     */
    public function __invoke(UserAdministrationService $svc, User $user): RedirectResponse
    {
        $this->authorize('manage', $user);

        $svc->clearTwoFaSettings($user);

        return back()->with('success', 'Two Factor Settings Reset');
    }
}
