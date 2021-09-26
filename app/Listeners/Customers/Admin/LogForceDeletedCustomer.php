<?php

namespace App\Listeners\Customers\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\Customers\Admin\CustomerForceDeletedEvent;

class LogForceDeletedCustomer
{
    /**
     * Handle the event
     */
    public function handle(CustomerForceDeletedEvent $event)
    {
        Log::channel('cust')->alert('Customer '.$event->cust->name.' has been perminanently deleted by '.Auth::user()->username.'.  Details - ', [
            'cust_id' => $event->cust->cust_id,
            'name'    => $event->cust->name,
        ]);
    }
}
