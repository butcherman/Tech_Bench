<?php

namespace App\Services\Auth;

use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Karmendra\LaravelAgentDetector\AgentDetector;

class TwoFactorService
{
    /**
     * Save in the session that the Two Factor process has been completed.  If
     * the user opted to save this device, generate a unique token that will
     * be saved as a cookie on the users machine.
     */
    public function processVerificationResponse(
        Collection $requestData,
        User $user,
        string $userAgent
    ): ?string {
        session()->put('2fa_verified', true);

        // Clear the code so it cannot be used again
        $user->UserVerificationCode->delete();

        return $requestData->get('remember')
            ? $this->generateRememberDeviceToken($user, $userAgent)
            : null;
    }

    /**
     * Generate a Remember token for a device
     */
    public function generateRememberDeviceToken(User $user, string $userAgent): string
    {
        $token = Str::random(60);
        $agent = new AgentDetector($userAgent);
        $ipAddr = request()->ip();

        $devToken = new DeviceToken([
            'token' => $token,
            'type' => $agent->device(),
            'os' => $agent->platform().' '.$agent->platformVersion(),
            'browser' => $agent->browser(),
            'registered_ip_address' => $ipAddr,
            'updated_ip_address' => $ipAddr,
        ]);

        $user->DeviceTokens()->save($devToken);

        return $token;
    }

    /**
     * Remove a device token
     */
    public function destroyDeviceToken(DeviceToken $token): void
    {
        $token->delete();
    }
}
