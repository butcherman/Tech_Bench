<?php

namespace App\Actions\Fortify;

use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Karmendra\LaravelAgentDetector\AgentDetector;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Laravel\Fortify\Fortify;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    /**
     * If the "Remember Device" flag is set, create a cookie to bypass 2fa.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toResponse($request)
    {
        $user = $request->user();

        if ($request->get('remember')) {
            $token = $this->generateRememberDeviceToken($user, $request->header('User-Agent'));

            return $request->wantsJson()
                ? new JsonResponse('', 204)
                : redirect()->intended(Fortify::redirects('login'))
                    ->withCookie('remember_device', $token, 259200);
        }

        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect()->intended(Fortify::redirects('login'));
    }

    /**
     * Generate a Remember token for a device
     */
    protected function generateRememberDeviceToken(User $user, string $userAgent): string
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
}
