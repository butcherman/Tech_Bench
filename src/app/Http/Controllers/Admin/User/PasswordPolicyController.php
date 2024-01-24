<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordPolicyRequest;
use Illuminate\Support\Facades\Log;

class PasswordPolicyController extends Controller
{
    /**
     * Display the resource.
     */
    public function show()
    {
        //
        return 'show password policy';
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
