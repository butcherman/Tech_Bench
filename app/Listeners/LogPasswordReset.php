<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\PasswordReset;

class LogPasswordReset
{
    public function handle(PasswordReset $event)
    {
        Log::notice('User'.Auth::user()->full_name.' has reset their password', ['User ID' => Auth::user()->user_id, 'Username' => Auth::user()->username, 'IP Address' => \Request::ip()]);
    }
}
