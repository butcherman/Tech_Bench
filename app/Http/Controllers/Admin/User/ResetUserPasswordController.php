<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetUserPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserPasswordRequest $request, User $user)
    {
        $user->forceFill([
            'password' => Hash::make($request->password),
            'password_expires' => $user->getNewExpireTime($request->changeRequired),
        ])->save();

        return back()->with('success', __('user.password_reset', ['user' => $user->full_name]));
    }
}
