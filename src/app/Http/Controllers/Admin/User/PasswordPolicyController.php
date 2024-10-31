<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\PasswordPolicyRequest;
use App\Models\User;
use App\Services\Admin\UserGlobalSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class PasswordPolicyController extends Controller
{
    public function __construct(protected UserGlobalSettingsService $svc) {}

    /**
     * Show the Password Policy Form.
     */
    public function edit(): Response
    {
        $this->authorize('manage', User::class);

        return Inertia::render('Admin/User/PasswordPolicy', [
            'policy' => $this->svc->getPasswordPolicy(),
        ]);
    }

    /**
     * Update the Password Policy Form.
     */
    public function update(PasswordPolicyRequest $request): RedirectResponse
    {
        $this->svc->savePasswordPolicy($request->safe()->collect());

        Log::notice(
            $request->user()->username.' has updated the User Password Policy',
            $request->toArray()
        );

        return back()->with('success', __('admin.user.password_policy'));
    }
}
