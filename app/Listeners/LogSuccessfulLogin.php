<?php

namespace App\Listeners;

use App\Models\UserLogins;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        UserLogins::create(
        [
            'user_id'    => Auth::user()->user_id,
            'ip_address' => \Request::ip()
        ]);

        Log::info('User '.Auth::user()->full_name.' logged in from IP Address '.\Request::ip(), ['User ID' => Auth::user()->user_id, 'Username' => Auth::user()->username, 'IP Address' => \Request::ip()]);
    }
}
