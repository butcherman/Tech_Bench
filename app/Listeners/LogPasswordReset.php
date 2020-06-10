<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogPasswordReset
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle()
    {
        Log::notice('User'.Auth::user()->full_name.' has reset their password', ['User ID' => Auth::user()->user_id, 'Username' => Auth::user()->username, 'IP Address' => \Request::ip()]);
    }
}
