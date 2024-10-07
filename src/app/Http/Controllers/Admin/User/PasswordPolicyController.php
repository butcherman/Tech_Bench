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
            'policy' => [
                'expire' => config('auth.passwords.settings.expire'),
                'min_length' => config('auth.passwords.settings.min_length'),
                'contains_uppercase' => (bool) config('auth.passwords.settings.contains_uppercase'),
                'contains_lowercase' => (bool) config('auth.passwords.settings.contains_lowercase'),
                'contains_number' => (bool) config('auth.passwords.settings.contains_number'),
                'contains_special' => (bool) config('auth.passwords.settings.contains_special'),
                'disable_compromised' => (bool) config('auth.passwords.settings.disable_compromised'),
            ],
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
