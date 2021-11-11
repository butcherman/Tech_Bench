<?php

namespace App\Listeners\Equipment;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\Equipment\EquipmentDataTypeDeletedEvent;

class LogEquipmentDataTypeDeleted
{
    /**
     * Handle the event
     */
    public function handle(EquipmentDataTypeDeletedEvent $event)
    {
        Log::info('Equipment Data Type has been created by '.Auth::user()->username.'.  Details - ', $event->type->toArray());
    }
}
