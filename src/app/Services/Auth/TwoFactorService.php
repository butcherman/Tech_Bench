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
        User $user
    ): ?string {
        session()->put('2fa_verified', true);

        // Clear the code so it cannot be used again
        $user->UserVerificationCode->delete();

        return $requestData->get('remember')
            ? $this->generateRememberDeviceToken($user)
            : null;
    }

    /**
     * Generate a Remember token for a device
     */
    public function generateRememberDeviceToken(User $user): string
    {
        $token = Str::random(60);
        $agent = new AgentDetector(request()->header('User-Agent'));
        $ipAddr = request()->ip();

        $devToken = new DeviceToken([
            // 'user_id' => $user->user_id,
            'token' => $token,
            'type' => $agent->device(),
            'os' => $agent->platform().' '.$agent->platformVersion(),
            'browser' => $agent->browser(),
            'registered_ip_address' => $ipAddr,
            'updated_ip_address' => $ipAddr,
        ]);

        $user->DeviceToken()->save($devToken);

        return $token;
    }
}