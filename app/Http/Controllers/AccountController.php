<?php

namespace App\Http\Controllers;

use App\Domains\Users\GetUserDetails;
use App\Domains\Users\SetUserDetails;

use App\Http\Requests\UserBasicAccountRequest;
use App\Http\Requests\UserChangePasswordRequest;
use App\Http\Requests\UserNotificationSettingsRequest;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Index page is the change user settings form
    public function index()
    {
        $userObj = new GetUserDetails;

        return view('account.index', [
            'userData'     => $userObj->getUserData(),
            'userSettings' => $userObj->getUserSettings(),
        ]);
    }

    //  Submit the new user settings
    public function submit(UserBasicAccountRequest $request)
    {
        (new SetUserDetails)->updateUserDetails($request);
        return response()->json(['success' => true]);
    }

    //  Submit the user notification settings
    public function notifications(UserNotificationSettingsRequest $request)
    {
        (new SetUserDetails)->updateUserNotifications($request);
        return response()->json(['success' => true]);
    }

    //  Bring up the change password form
    public function changePassword()
    {
        return view('account.changePassword');
    }

    //  Submit the change password form
    public function submitPassword(UserChangePasswordRequest $request)
    {
        (new SetUserDetails)->updateUserPassword($request->newPass);
        return redirect(route('account'))->with('success', 'Password Changed Successfully');
    }
}
