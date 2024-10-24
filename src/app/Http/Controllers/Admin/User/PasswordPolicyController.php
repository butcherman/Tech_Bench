<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordPolicyRequest;
use App\Models\User;
use App\Service\Admin\ApplicationSettingsService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PasswordPolicyController extends Controller
{
    public function __construct(protected ApplicationSettingsService $svc) {}

    /**
     * Display the current password policy.
     */
    public function show(): Response
    {
        $this->authorize('viewAny', User::class);

        return Inertia::render('Admin/User/PasswordPolicy', [
            'policy' => $this->svc->getPasswordSettings(),
        ]);
    }

    /**
     * Update the password policy.
     */
    public function update(PasswordPolicyRequest $request): RedirectResponse
    {
        $this->svc->processPasswordSettings($request);

        return back()->with('success', __('admin.user.password_policy'));
    }
}
