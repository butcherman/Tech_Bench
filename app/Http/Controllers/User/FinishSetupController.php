<?php

namespace App\Http\Controllers\User;

use App\Events\UserInitializedEvent;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\FinishSetupRequest;
use App\Models\UserInitialize;

class FinishSetupController extends Controller
{
    /**
     * Finish setting up a user account
     */
    public function __invoke(FinishSetupRequest $request)
    {
        $link = UserInitialize::where('username', $request->username)->firstOrFail();

        //  Determine the new expiration date
        $expires = config('auth.passwords.settings.expire') ? Carbon::now()->addDays(config('auth.passwords.settings.expire')) : null;

        //  Set the users new password
        $user = User::where('username', $request->username)->firstOrFail();
        $user->update(['password' => Hash::make($request->password), 'password_expires' => $expires]);
        $user->save();

        //  Delete the Initialization Link
        $link->delete();

        //  Log the user in and send to the Dashboard
        Auth::login($user);

        event(new UserInitializedEvent($user));
        return redirect(route('dashboard'))->with([
            'message' => 'Your Account is setup',
            'type'    => 'success',
        ]);
    }
}
