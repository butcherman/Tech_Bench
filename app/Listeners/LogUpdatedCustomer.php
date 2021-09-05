<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\CustomerDetailsUpdated;

class LogUpdatedCustomer
{
    /**
     * Handle the event
     */
    public function handle(CustomerDetailsUpdated $event)
    {
        Log::channel('cust')->info('User '.Auth::user()->username.' has updated Customer ID '.$event->cust_id.'.  New Data - ', $event->details->toArray());
    }
}
