<?php

namespace App\Listeners;

use App\UserLogins;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        
        Log::notice('User Logged In ', $user->toArray());
    }
}
