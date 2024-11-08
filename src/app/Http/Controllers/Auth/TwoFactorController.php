<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerificationCodeRequest;
use App\Services\Auth\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorController extends Controller
{
    public function __construct(protected TwoFactorService $svc) {}

    /**
     * Show the 2FA Verification Form.
     */
    public function show(): Response
    {
        return Inertia::render('Auth/TwoFactorAuth', [
            'allow-remember' => fn () => config('auth.twoFa.allow_save_device'),
        ]);
    }

    /**
     * Validate and process the 2FA Code
     */
    public function update(VerificationCodeRequest $request): RedirectResponse
    {
        $cookie = $this->svc
            ->processVerificationResponse(
                $request->safe()->collect(),
                $request->user(),
                $request->header('User-Agent')
            );

        return redirect()
            ->intended(route('dashboard'))
            ->withCookie('remember_device', $cookie, 259200);
    }
}
