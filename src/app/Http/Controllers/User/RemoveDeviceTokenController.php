<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\User;
use App\Services\Auth\TwoFactorService;
use Illuminate\Http\RedirectResponse;

class RemoveDeviceTokenController extends Controller
{
    public function __construct(protected TwoFactorService $svc) {}

    /**
     * Remove a 2FA Device Token
     */
    public function __invoke(User $user, DeviceToken $token): RedirectResponse
    {
        $this->authorize('update', $user);

        $this->svc->destroyDeviceToken($token);

        return back()->with('success', __('user.device-removed'));
    }
}
