<?php

// TODO - Refactor

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordPolicyRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PasswordPolicyController extends Controller
{
    /**
     * Display the resource.
     */
    public function show()
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
     * Update the resource in storage.
     */
    public function update(PasswordPolicyRequest $request)
    {
        $request->processPasswordSettings();

        Log::notice($request->user()->username.' has updated the User Password Policy',
            $request->toArray());

        return back()->with('success', __('admin.user.password_policy'));

    }
}
