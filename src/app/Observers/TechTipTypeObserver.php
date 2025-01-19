<?php

namespace App\Observers;

use App\Facades\CacheFacade;
use App\Models\TechTipType;
use Illuminate\Support\Facades\Log;

class TechTipTypeObserver extends Observer
{
    /**
     * Handle the TechTipType "created" event.
     */
    public function created(TechTipType $techTipType): void
    {
        CacheFacade::clearCache('techTipTypes');

        Log::info(
            'New Tech Tip Type created by ' . $this->user,
            $techTipType->toArray()
        );
    }

    /**
     * Handle the TechTipType "updated" event.
     */
    public function updated(TechTipType $techTipType): void
    {
        CacheFacade::clearCache('techTipTypes');

        Log::info(
            'Tech Tip Type updated by ' . $this->user,
            $techTipType->toArray()
        );
    }

    /**
     * Handle the TechTipType "deleted" event.
     */
    public function deleted(TechTipType $techTipType): void
    {
        CacheFacade::clearCache('techTipTypes');

        Log::info(
            'Tech Tip Type deleted by ' . $this->user,
            $techTipType->toArray()
        );
    }
}
