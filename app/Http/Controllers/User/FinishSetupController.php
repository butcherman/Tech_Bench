<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserInitialize;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\FinishSetupRequest;

class FinishSetupController extends Controller
{
    /**
     * Finish setting up a user account
     */
    public function __invoke(FinishSetupRequest $request, UserInitialize $initLink)
    {
        // Assign the users password
        $user = User::where('username', $initLink->username)->first();
        $user->update([
            'password' => Hash::make($request->password),
            'expires'  => $user->getNewExpireTime(),
        ]);

        //  Clear the Initialization link
        $initLink->delete();

        //  Login the user
        Auth::login($user);

        Log::channel('user')->info('User'.$user->username.' has finished setting up their account');
        return redirect(route('dashboard'))->with('success', __('user.setup_completed'));
    }
}
