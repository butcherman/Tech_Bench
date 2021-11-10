<?php

namespace App\Listeners\Equipment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Events\Equipment\EquipmentTypeDeletedEvent;

class LogEquipmentTypeDeleted
{
    /**
     * Handle the event
     */
    public function handle(EquipmentTypeDeletedEvent $event)
    {
        Log::info('Equipment Type deleted by '.Auth::user()->username.'.  Details - ', $event->equipmentType->toArray());
    }
}
