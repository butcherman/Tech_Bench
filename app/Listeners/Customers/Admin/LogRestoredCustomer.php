<?php

namespace App\Listeners\Customers\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\Customers\Admin\CustomerRestoredEvent;

class LogRestoredCustomer
{
    /**
     * Handle the event
     */
    public function handle(CustomerRestoredEvent $event)
    {
        Log::channel('cust')->notice('Customer '.$event->cust->name.' has been restored by '.Auth::user()->username.'.  Details - ', [
            'cust_id' => $event->cust->cust_id,
            'name'    => $event->cust->name,
        ]);
    }
}
