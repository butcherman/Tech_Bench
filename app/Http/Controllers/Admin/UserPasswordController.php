<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangeUserPasswordRequest;
use App\Models\User;

class UserPasswordController extends Controller
{
    /**
     * Show the form for editing the users password
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return Inertia::render('Admin/User/ResetPassword', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified users password
     */
    public function update(ChangeUserPasswordRequest $request, User $user)
    {
        $expires = $user->getNewExpireTime();
        $user->update([
            'password'         => Hash::make($request->password),
            'password_expires' => $expires
        ]);

        return redirect(route('admin.user.index'))->with('success', __('user.password_updated'));
    }
}
