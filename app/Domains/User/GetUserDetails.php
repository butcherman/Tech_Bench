<?php

namespace App\Domains\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\User;
use App\UserSettings;

class GetUserDetails
{
    protected $userID;

    public function __construct($userID = null)
    {
        $this->userID = $userID ? $userID : Auth::user()->user_id;
    }

    public function getUserData()
    {
        $userData = User::find($this->userID)->makeVisible('username');
        Log::debug('User Data gathered for User ID '.$this->userID.'.  Gathered Data - ', $userData->toArray());

        return $userData;
    }

    public function getUserSettings()
    {
        $userSett = UserSettings::where('user_id', $this->userID)->first();
        Log::debug('User Settings gathered for User ID '.$this->userID.'.  Gathered Data - ', $userSett->toArray());

        return $userSett;
    }

    //  Determine if a username or email address is already in use
    public function checkForDuplicate($type, $value)
    {
        return User::where($type, $value)->first();
    }
}
