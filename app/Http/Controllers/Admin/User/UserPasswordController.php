<?php

namespace App\Http\Controllers\Admin\User;

use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangeUserPasswordRequest;

class UserPasswordController extends Controller
{
    /**
     * Show the Change Password form for an admin
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return Inertia::render('Admin/User/ResetPassword', [
            'user' => $user,
        ]);
    }

    /**
     * Submit the change user password form
     */
    public function update(ChangeUserPasswordRequest $request, User $user)
    {
        $user->update([
            'password'         => Hash::make($request->password),
            'password_expires' => $user->getNewExpireTime(),
        ]);

        Log::channel('user')->info('Password for '.$user->username.' has been changed by '.$request->user()->username);
        return redirect(route('admin.users.index'))->with('success', __('user.password_updated'));
    }
}
