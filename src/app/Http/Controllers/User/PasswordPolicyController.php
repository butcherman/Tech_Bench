<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Admin\PasswordPolicyRequest as AdminPasswordPolicyRequest;
use App\Models\User;
use App\Services\User\UserGlobalSettingsService as UserUserGlobalSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class PasswordPolicyController extends Controller
{
    public function __construct(protected UserUserGlobalSettingsService $svc) {}

    /**
     * Show the Password Policy Form.
     */
    public function edit(): Response
    {
        $this->authorize('viewAny', User::class);

        return Inertia::render('Auth/PasswordPolicy', [
            'policy' => fn () => $this->svc->getPasswordPolicy(),
        ]);
    }

    /**
     * Update the Password Policy Form.
     */
    public function update(AdminPasswordPolicyRequest $request): RedirectResponse
    {
        $this->svc->savePasswordPolicy($request->safe()->collect());

        Log::notice(
            $request->user()->username.' has updated the User Password Policy',
            $request->toArray()
        );

        return back()->with('success', __('admin.user.password_policy'));
    }
}
