<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Domains\User\GetUserDetails;
use App\Domains\User\SetUserDetails;

use App\Http\Requests\User\UserAccountRequest;
use App\Http\Requests\User\UserPasswordRequest;
use App\Http\Requests\User\UserSettingsRequest;

class SettingsController extends Controller
{
    public function index()
    {
        $userObj = new GetUserDetails(Auth::user()->user_id);

        return view('auth.settings', [
            'userData' => $userObj->getUserData(),
            'userSett' => $userObj->getUserSettings(),
        ]);
    }

    public function updateAccount(UserAccountRequest $request)
    {
        (new SetUserDetails)->updateUser($request, Auth::user()->user_id);
        return response()->json(['success' => true]);
    }

    public function updateSettings(UserSettingsRequest $request)
    {
        (new SetUserDetails)->updateSettings($request, Auth::user()->user_id);
        return response()->json(['success' => true]);
    }

    public function changePassword(UserPasswordRequest $request)
    {
        (new SetUserDetails)->updatePassword($request->password, Auth::user()->user_id);
        return response()->json(['success' => true]);
    }
}
