<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Rules\ValidatePassword;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Index page is the change user settings form
    public function index()
    {
        $userData = User::find(Auth::user()->user_id);
        $userSett = UserSettings::where('user_id', Auth::user()->user_id)->first();

        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        return view('account.index', [
            'userData'     => $userData,
            'userSettings' => $userSett,
            'userID'       => Auth::user()->user_id
        ]);
    }

    //  Submit the new user settings
    public function submit(Request $request)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data: ', $request->toArray());

        $request->validate([
            'username'   => 'required',
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => [
                'required',
                Rule::unique('users')->ignore(Auth::user())
            ],
        ]);

        $userID = Auth::user()->user_id;
        User::find($userID)->update(
        [
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email
        ]);

        session()->flash('success', 'User Settings Updated');

        Log::notice('User Settings updated for '.Auth::user()->full_name);
        return redirect(route('account'));
    }

    //  Submit the user notification settings
    public function notifications(Request $request)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data: ', $request->toArray());

        UserSettings::where('user_id', Auth::user()->user_id)->update(
        [
            'em_tech_tip'     => $request->em_tech_tip === 'on' ? true : false,
            'em_file_link'    => $request->em_file_link === 'on' ? true : false,
            'em_notification' => $request->em_notification === 'on' ? true : false,
            'auto_del_link'   => $request->auto_del_link === 'on' ? true : false,
        ]);

        session()->flash('success', 'User Notifications Updated');

        Log::notice('User Notifications updated for '.Auth::user()->full_name);
        return redirect(route('account'));
    }

    //  Bring up the change password form
    public function changePassword()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        return view('account.changePassword');
    }

    //  Submit the change password form
    public function submitPassword(Request $request)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        //  Validate form data
        $request->validate([
            'oldPass' => ['required', new ValidatePassword],
            'newPass' => 'required|string|min:6|confirmed|different:oldPass'
        ]);

        //  Determine if there is a new password expire's date
        $newExpire = config('auth.passwords.settings.expire') != null ? Carbon::now()->addDays(config('auth.passwords.settings.expire')) : null;

        //  Change the password
        $user = Auth::user();
        $user->password = bcrypt($request->newPass);
        $user->password_expires = $newExpire;
        $user->save();

        Log::notice('User password changed for '.Auth::user()->full_name);

        return redirect(route('account'))->with('success', 'Password Changed Successfully');
    }
}
