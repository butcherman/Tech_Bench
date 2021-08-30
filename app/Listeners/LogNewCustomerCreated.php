<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\NewCustomerCreated;

class LogNewCustomerCreated
{
    /**
     * Handle the event
     */
    public function handle(NewCustomerCreated $event)
    {
        Log::stack(['cust', 'user'])->info('New Customer '.$event->customer->name.' created by '.Auth::user()->username);
    }
}
