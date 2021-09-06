<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use App\Events\CustomerEquipmentDeletedEvent;
use Illuminate\Support\Facades\Auth;

class LogDeletedCustomerEquipment
{
    /**
     * Handle the event
     */
    public function handle(CustomerEquipmentDeletedEvent $event)
    {
        Log::channel('cust')->info('Equipment has been deleted for '.$event->cust->name.' by '.Auth::user()->username.'.  Details - ', [
            'cust_id'               => $event->cust->cust_id,
            'equipment'             => $event->equip->name,
            'equipment category id' => $event->equip->cat_id,
        ]);
    }
}
