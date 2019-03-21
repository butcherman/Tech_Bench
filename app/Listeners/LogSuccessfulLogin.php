<?php

namespace App\Listeners;

use App\UserLogins;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSuccessfulLogin
{
    //  Update the datbase to note the successful login
    public function handle($event)
    {
        UserLogins::create(
        [
            'user_id'    => Auth::user()->user_id,
            'ip_address' => \Request::ip()
        ]);
    }
}
