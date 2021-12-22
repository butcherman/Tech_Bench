<?php

namespace App\Listeners\Equipment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Events\Equipment\EquipmentCategoryCreatedEvent;

class LogEquipmentCategoryCreated
{
    /**
     * Handle the event
     */
    public function handle(EquipmentCategoryCreatedEvent $event)
    {
        Log::info('Equipment Category created by '.Auth::user()->username.'.  Details - ', $event->category->toArray());
    }
}
