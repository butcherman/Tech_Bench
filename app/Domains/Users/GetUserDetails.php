<?php

namespace App\Domains\Users;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
        Log::debug('User Data gathered for User ID '.$this->userID.'.  Gathered Data - ', array($userData));

        return $userData;
    }

    public function getUserSettings()
    {
        $userSett = UserSettings::where('user_id', $this->userID)->first();
        Log::debug('User Settings gathered for User ID '.$this->userID.'.  Gathered Data - ', array($userSett));

        return $userSett;
    }
}
