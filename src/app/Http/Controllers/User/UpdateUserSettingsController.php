<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserSettingsRequest;
use App\Models\User;
use App\Services\User\UserSettingsService;
use Illuminate\Http\RedirectResponse;

class UpdateUserSettingsController extends Controller
{
    public function __construct(protected UserSettingsService $svc) {}

    /**
     * Update the users Application Settings
     */
    public function __invoke(UserSettingsRequest $request, User $user): RedirectResponse
    {
        $this->svc->updateUserSettings($request->safe()->collect(), $user);

        return back()->with('success', __('user.updated'));
    }
}
