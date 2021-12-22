<?php

namespace App\Listeners\Customers\Equipment;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\Customers\Equipment\CustomerEquipmentForceDeletedEvent;

class LogForceDeletedCustomerEquipment
{
    /**
     * Handle the event
     */
    public function handle(CustomerEquipmentForceDeletedEvent $event)
    {
        Log::channel('cust')->info('Equipment has been permanently deleted for '.$event->cust->name.' by '.Auth::user()->username.'.  Details - ', [
            'cust_id'               => $event->cust->cust_id,
            'equipment'             => $event->equip->name,
            'equipment category id' => $event->equip->cat_id,
        ]);
    }
}
