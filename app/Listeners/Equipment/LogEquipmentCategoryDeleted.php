<?php

namespace App\Listeners\Equipment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Events\Equipment\EquipmentCategoryDeletedEvent;

class LogEquipmentCategoryDeleted
{
    /**
     * Handle the event
     */
    public function handle(EquipmentCategoryDeletedEvent $event)
    {
        Log::info('Equipment Category deleted by '.Auth::user()->username.'.  Details - ', $event->category->toArray());
    }
}
