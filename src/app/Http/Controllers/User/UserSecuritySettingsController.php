<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserSecurityRequest;
use App\Models\AppSettings;
use App\Services\User\UserGlobalSettingsService;
use App\Traits\UserRoleTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserSecuritySettingsController extends Controller
{
    use UserRoleTrait;

    public function __construct(protected UserGlobalSettingsService $svc) {}

    /**
     * Show the form for editing the resource.
     */
    public function edit(Request $request): Response
    {
        $this->authorize('update', AppSettings::class);

        return Inertia::render('User/Admin/UserSecuritySettings', [
            'auto-logout-timer' => fn () => intval(config('auth.auto_logout_timer')),
            'two-fa' => fn () => $this->svc->getTwoFaConfig(),
            'oath' => fn () => $this->svc->getOathConfig(),
            'role-list' => fn () => $this->getAvailableRoles($request->user()),
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(UserSecurityRequest $request): RedirectResponse
    {
        $this->svc->updateUserSettingsConfig($request->safe()->collect());

        return back()->with('success', __('admin.user.settings_updated'));
    }
}
