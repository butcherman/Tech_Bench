<?php

namespace App\Observers;

use App\Models\TechTip;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TechTipObserver extends Observer
{
    /**
     * Handle the TechTip "created" event.
     */
    public function created(TechTip $techTip): void
    {
        //
    }

    /**
     * Handle the TechTip "updated" event.
     */
    public function updated(TechTip $techTip): void
    {
        //
    }

    /**
     * Handle the TechTip "deleted" event.
     */
    public function deleted(TechTip $techTip): void
    {
        //
    }

    /**
     * Handle the TechTip "restored" event.
     */
    public function restored(TechTip $techTip): void
    {
        //
    }

    /**
     * Handle the TechTip "force deleted" event.
     */
    public function forceDeleted(TechTip $techTip): void
    {
        //
    }
}
