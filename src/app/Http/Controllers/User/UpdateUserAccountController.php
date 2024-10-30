<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserAccountRequest;
use App\Models\User;
use App\Services\User\UserSettingsService;
use Illuminate\Http\RedirectResponse;

class UpdateUserAccountController extends Controller
{
    public function __construct(protected UserSettingsService $svc) {}

    /**
     * Update the basic user information for a user account.
     */
    public function __invoke(UserAccountRequest $request, User $user): RedirectResponse
    {
        $this->svc->updateUserAccount($request->safe()->collect(), $user);

        return back()->with('success', __('user.updated'));
    }
}
