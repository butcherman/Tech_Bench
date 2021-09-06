<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\CustomerEquipmentAddedEvent;

class LogNewCustomerEquipment
{
    /**
     * Handle the event
     */
    public function handle(CustomerEquipmentAddedEvent $event)
    {
        Log::channel('cust')->info('New equipment has been added for '.$event->cust->name.' by '.Auth::user()->username.'.  Details - ', [
            'cust_id'               => $event->cust->cust_id,
            'equipment'             => $event->equip->name,
            'equipment category id' => $event->equip->cat_id,
        ]);
    }
}
