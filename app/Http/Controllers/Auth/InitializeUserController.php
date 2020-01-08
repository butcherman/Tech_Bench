<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use App\UserInitialize;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class InitializeUserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('guest');
    }

    //  Bring up the "Finish User Setup" form
    public function initializeUser($hash)
    {
        //  Validate the hash token
        $user = UserInitialize::where('token', $hash)->get();

        if ($user->isEmpty()) {
            Log::debug('Route ' . Route::currentRouteName() . ' visited by User ID-' . Auth::user()->user_id);
            Log::warning('Visitor at IP Address ' . \Request::ip() . ' tried to access invalid initialize hash - ' . $hash);
            return abort(404);
        }

        Log::debug('Route ' . Route::currentRouteName() . ' visited.');
        Log::debug('Link Hash -' . $hash);
        return view('account.initializeUser', ['hash' => $hash]);
    }

    //  Submit the initialize user form
    public function submitInitializeUser(Request $request, $hash)
    {
        //  Verify that the link matches the assigned email address
        $valid = UserInitialize::where('token', $hash)->first();
        if (empty($valid)) {
            Log::debug('Route ' . Route::currentRouteName() . ' visited by User ID-' . Auth::user()->user_id);
            Log::warning('Visitor at IP Address ' . \Request::ip() . ' tried to submit an invalid User Initialization link - ' . $hash);
            return abort(404);
        }

        //  Validate the form
        $request->validate([
            'username' => [
                'required',
                Rule::in([$valid->username]),
            ],
            'newPass'  => 'required|string|min:6|confirmed'
        ]);

        //  Get the users information
        $userData = User::where('username', $valid->username)->first();

        $nextChange = config('auth.passwords.settings.expire') != null ? Carbon::now()->addDays(config('auth.passwords.settings.expire')) : null;

        //  Update the password
        User::find($userData->user_id)->update(
            [
                'password'         => bcrypt($request->newPass),
                'password_expires' => $nextChange
            ]
        );

        //  Remove the initialize instance
        UserInitialize::find($valid->id)->delete();

        //  Log in the user
        Auth::loginUsingID($userData->user_id);

        //  Redirect the user to the dashboard
        Log::debug('Route ' . Route::currentRouteName() . ' visited by User ID-' . Auth::user()->user_id);
        Log::debug('Initialize Data - ', $request->toArray());
        Log::notice('User has setup account', ['user_id' => $userData->user_id]);
        return redirect(route('dashboard'));
    }
}
