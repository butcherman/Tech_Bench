<?php

namespace App\Observers;

use App\Models\TechTipType;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TechTipTypeObserver extends Observer
{
    /**
     * Handle the TechTipType "created" event.
     */
    public function created(TechTipType $techTipType): void
    {
        //
    }

    /**
     * Handle the TechTipType "updated" event.
     */
    public function updated(TechTipType $techTipType): void
    {
        //
    }

    /**
     * Handle the TechTipType "deleted" event.
     */
    public function deleted(TechTipType $techTipType): void
    {
        //
    }

    /**
     * Handle the TechTipType "restored" event.
     */
    public function restored(TechTipType $techTipType): void
    {
        //
    }

    /**
     * Handle the TechTipType "force deleted" event.
     */
    public function forceDeleted(TechTipType $techTipType): void
    {
        //
    }
}
