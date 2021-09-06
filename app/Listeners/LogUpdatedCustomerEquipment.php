<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\CustomerEquipmentUpdatedEvent;

class LogUpdatedCustomerEquipment
{
    /**
     * Handle the event
     */
    public function handle(CustomerEquipmentUpdatedEvent $event)
    {
        Log::channel('cust')->info('Equipment has been updated for '.$event->cust->name.' by '.Auth::user()->username.'.  Details - ', [
            'cust_id'               => $event->cust->cust_id,
            'equipment'             => $event->equip->name,
            'equipment category id' => $event->equip->cat_id,
        ]);
    }
}
