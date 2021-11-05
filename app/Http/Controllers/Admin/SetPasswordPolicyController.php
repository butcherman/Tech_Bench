<?php

namespace App\Http\Controllers\Admin;

use App\Events\Admin\PasswordPolicyUpdatedEvent;
use App\Models\AppSettings;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordPolicyRequest;

class SetPasswordPolicyController extends Controller
{
    /**
     * Update the users' password policy
     */
    public function __invoke(PasswordPolicyRequest $request)
    {
        //  Set the new password policy expiration timer
        AppSettings::firstOrCreate(
            ['key' => 'auth.passwords.settings.expire'],
            ['key' => 'auth.passwords.settings.expire', 'value' => $request->password_expires],
        )->update([
            'value' => $request->password_expires
        ]);

        //  TODO - Event that sets or clears all users password expiration dates

        event(new PasswordPolicyUpdatedEvent($request->password_expires));
        return back()->with([
            'message' => 'Password Policy Updated',
            'type'    => 'success',
        ]);
    }
}
