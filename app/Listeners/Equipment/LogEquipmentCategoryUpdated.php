<?php

namespace App\Listeners\Equipment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Events\Equipment\EquipmentCategoryUpdatedEvent;

class LogEquipmentCategoryUpdated
{
    /**
     * Handle the event
     */
    public function handle(EquipmentCategoryUpdatedEvent $event)
    {
        Log::info('Equipment Category updated by '.Auth::user()->username.'.  Details - ', $event->category->toArray());
    }
}
