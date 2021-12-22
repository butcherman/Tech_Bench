<?php

namespace App\Listeners\Equipment;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\Equipment\EquipmentDataTypeUpdatedEvent;

class LogEquipmentDataTypeUpdated
{
    /**
     * Handle the event
     */
    public function handle(EquipmentDataTypeUpdatedEvent $event)
    {
        Log::info('Equipment Data Type has been created by '.Auth::user()->username.'.  Details - ', $event->type->toArray());
    }
}
