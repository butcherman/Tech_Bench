<?php

namespace App\Listeners\Equipment;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\Equipment\EquipmentTypeUpdatedEvent;

class LogEquipmentTypeUpdated
{
    /**
     * Handle the event
     */
    public function handle(EquipmentTypeUpdatedEvent $event)
    {
        Log::info('Equipment Type updated by '.Auth::user()->username.'.  Details - ', $event->equipmentType->toArray());
    }
}
