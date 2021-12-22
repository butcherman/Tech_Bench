<?php

namespace App\Listeners\Equipment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Events\Equipment\EquipmentTypeCreatedEvent;

class LogEquipmentTypeCreated
{
    /**
     * Handle the event
     */
    public function handle(EquipmentTypeCreatedEvent $event)
    {
        Log::info('Equipment Type created by '.Auth::user()->username.'.  Details - ', $event->equipmentType->toArray());
    }
}
