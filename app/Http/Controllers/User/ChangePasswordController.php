<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    /**
     * Change the password for the currently logged in user
     */
    public function __invoke(ChangePasswordRequest $request)
    {
        $user = User::find($request->user()->user_id);
        $user->update([
            'password'         => Hash::make($request->password),
            'password_expires' => $user->getNewExpireTime(),
        ]);

        Log::channel('user')->info('User '.$request->user()->username.' has updated their password');
        return redirect(route('dashboard'))->with('success', __('user.password_changed'));
    }
}
