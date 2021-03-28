<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogSuccessfulLogout
{
    public function handle(Logout $event)
    {
        Log::info('User '.Auth::user()->full_name.' logged out', ['User ID' => Auth::user()->user_id, 'Username' => Auth::user()->username, 'IP Address' => \Request::ip()]);
    }
}
