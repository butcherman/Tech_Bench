<?php

namespace App\Listeners;

use App\UserLogins;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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

        Log::info('User '.Auth::user()->full_name.' logged in from IP Address '.\Request::ip(), $user->toArray());
    }
}
