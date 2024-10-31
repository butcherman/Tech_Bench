<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\PasswordPolicyRequest;
use App\Models\User;
use App\Services\Admin\UserGlobalSettingsService;
use Inertia\Inertia;

class PasswordPolicyController extends Controller
{
    public function __construct(protected UserGlobalSettingsService $svc) {}

    /**
     * Show the form for editing the resource.
     */
    public function edit()
    {
        $this->authorize('manage', User::class);

        return Inertia::render('Admin/User/PasswordPolicy', [
            'policy' => $this->svc->getPasswordPolicy(),
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(PasswordPolicyRequest $request)
    {
        $this->svc->savePasswordPolicy($request->safe()->collect());

        return back()->with('success', __('admin.user.password_policy'));
    }
}
