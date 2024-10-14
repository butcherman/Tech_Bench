<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserSettingsRequest;
use App\Models\User;
use App\Models\UserRole;
use App\Service\Admin\UserSettingsAdministrationService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserSettingsController extends Controller
{
    public function __construct(protected UserSettingsAdministrationService $svc) {}

    /**
     * Display the current User Administration Settings.
     */
    public function show(): Response
    {
        $this->authorize('create', User::class);

        return Inertia::render('Admin/User/UserSettings', [
            'auto-logout-timer' => intval(config('auth.auto_logout_timer')),
            'two-fa' => $this->svc->getTwoFaConfig(),
            'oath' => $this->svc->getOathConfig(),
            'role-list' => UserRole::all(),
        ]);
    }

    /**
     * Update the User Administration Settings.
     */
    public function update(UserSettingsRequest $request): RedirectResponse
    {
        $this->svc->updateUserSettingsConfig($request);

        return back()->with('success', __('admin.user.settings_updated'));
    }
}
