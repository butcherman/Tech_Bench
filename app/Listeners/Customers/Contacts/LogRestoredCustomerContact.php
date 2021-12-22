<?php

namespace App\Listeners\Customers\Contacts;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\Customers\Contacts\CustomerContactRestoredEvent;

class LogRestoredCustomerContact
{
    /**
     * Handle the event
     */
    public function handle(CustomerContactRestoredEvent $event)
    {
        Log::channel('cust')->info('Customer Contact ID '.$event->cont->cont_id.' restored for Customer '.$event->cust->name.' by '.Auth::user()->username.'.  Details - ', [
            'Contact Name' => $event->cont->name,
            'Contact ID'   => $event->cont->cont_id,
            'Customer ID'  => $event->cust->cust_id,
        ]);
    }
}
