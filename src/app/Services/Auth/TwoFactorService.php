<?php

namespace App\Services\Auth;

use App\Enums\TwoFactorMethod;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Http\Request;

class TwoFactorService
{
    /**
     * Get the user trying to authenticate
     */
    public function getChallengedUser(Request $request): ?User
    {
        $userId = $request->session()->get('login.id');
        $user = User::find($userId);

        if (! $user) {
            return null;
        }

        return $user;
    }

    /**
     * Get the method we will be using for 2FA
     */
    public function getMfaMethod(User $user)
    {
        return TwoFactorMethod::tryFrom($user->two_factor_via);
    }

    /**
     * Check for verification process and complete the necessary steps
     */
    public function provideChallenge(User $user)
    {
        $via = $this->getMfaMethod($user);

        if ($via === TwoFactorMethod::Email) {
            $user->generateVerificationCode();
        }
    }

    /**
     * Remove a device token
     */
    public function destroyDeviceToken(DeviceToken $token): void
    {
        $token->delete();
    }
}
