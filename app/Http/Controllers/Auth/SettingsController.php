<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Domains\User\GetUserDetails;
use App\Domains\User\SetUserDetails;

use App\Http\Requests\User\UserAccountRequest;
use App\Http\Requests\User\UserPasswordRequest;
use App\Http\Requests\User\UserSettingsRequest;

class SettingsController extends Controller
{
    //  Open the Individual User Settings Form
    public function index()
    {
        $userObj = new GetUserDetails(Auth::user()->user_id);

        return view('auth.settings', [
            'userData' => $userObj->getUserData(),
            'userSett' => $userObj->getUserSettings(),
        ]);
    }

    //  Update basic User information
    public function updateAccount(UserAccountRequest $request)
    {
        (new SetUserDetails)->updateUser($request, Auth::user()->user_id);
        Log::notice('User '.Auth::user()->full_name.' has updated their Account Settings.  Details - ', $request->toArray());

        return response()->json(['success' => true]);
    }

    //  Update Email notification settings
    public function updateSettings(UserSettingsRequest $request)
    {
        (new SetUserDetails)->updateSettings($request, Auth::user()->user_id);
        Log::notice('User '.Auth::user()->full_name.' has updated their Notification Settings.  Details - ', $request->toArray());

        return response()->json(['success' => true]);
    }

    //  Update the users password
    public function changePassword(UserPasswordRequest $request)
    {
        (new SetUserDetails)->updatePassword($request->password, Auth::user()->user_id);
        Log::notice('Password has been changed for User ID '.Auth::user()->user_id.' by '.Auth::user()->full_name);

        return response()->json(['success' => true]);
    }
}
