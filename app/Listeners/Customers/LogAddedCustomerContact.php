<?php

namespace App\Listeners\Customers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\Customers\CustomerContactAddedEvent;

class LogAddedCustomerContact
{
    /**
     * Handle the event
     */
    public function handle(CustomerContactAddedEvent $event)
    {
        Log::channel('cust')->info('Customer Contact ID '.$event->cont->cont_id.' downloaded for Customer '.$event->cust->name.' by '.Auth::user()->username.'.  Details - ', [
            'Contact Name' => $event->cont->name,
            'Contact ID'   => $event->cont->cont_id,
            'Customer ID'  => $event->cust->cust_id,
        ]);
    }
}
