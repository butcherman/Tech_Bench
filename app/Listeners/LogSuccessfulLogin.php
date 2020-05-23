<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\UserLogins;

class LogSuccessfulLogin
{
    //  Update the datbase to note the successful login
    public function handle()
    {
        $user = UserLogins::create(
        [
            'user_id'    => Auth::user()->user_id,
            'ip_address' => \Request::ip()
        ]);

        Log::info('User '.Auth::user()->full_name.' logged in from IP Address '.\Request::ip(), $user->toArray(), ['User ID' => Auth::user()->user_id, 'Username' => Auth::user()->username, 'IP Address' => \Request::ip()]);
    }
}
