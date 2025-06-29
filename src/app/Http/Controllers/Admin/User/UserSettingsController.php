<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserSettingsRequest;
use App\Models\AppSettings;
use App\Services\Admin\UserGlobalSettingsService;
use App\Traits\UserRoleTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class UserSettingsController extends Controller
{
    use UserRoleTrait;

    public function __construct(protected UserGlobalSettingsService $svc) {}

    /**
     * Show the form for editing the resource.
     */
    public function edit(Request $request): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/User/UserSettings', [
            'auto-logout-timer' => fn () => intval(config('auth.auto_logout_timer')),
            'two-fa' => fn () => $this->svc->getTwoFaConfig(),
            'oath' => fn () => $this->svc->getOathConfig(),
            'role-list' => fn () => $this->getAvailableRoles($request->user()),
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(UserSettingsRequest $request): RedirectResponse
    {
        $this->svc->updateUserSettingsConfig($request->safe()->collect());

        Log::notice(
            'User Administration Settings updated by '.request()->user()->username,
            $request->except(['client_secret'])
        );

        return back()->with('success', __('admin.user.settings_updated'));
    }
}
