<?php

namespace App\Listeners\Equipment;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\Equipment\EquipmentDataTypeCreatedEvent;

class LogEquipmentDataTypeCreated
{
    /**
     * Handle the event
     */
    public function handle(EquipmentDataTypeCreatedEvent $event)
    {
        Log::info('Equipment Data Type has been created by '.Auth::user()->username.'.  Details - ', $event->type->toArray());
    }
}
