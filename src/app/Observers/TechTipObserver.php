<?php

namespace App\Observers;

use App\Events\File\FileDataDeletedEvent;
use App\Models\TechTip;
use App\Models\TechTipView;
use Illuminate\Support\Facades\Log;

class TechTipObserver extends Observer
{
    /**
     * Handle the TechTip "created" event.
     */
    public function created(TechTip $techTip): void
    {
        // Create the Views Table Entry
        $techTip->TechTipViews()->save(new TechTipView);

        Log::info('New Tech Tip created by ' . $this->user, $techTip->toArray());
    }

    /**
     * Handle the TechTip "updated" event.
     */
    public function updated(TechTip $techTip): void
    {
        Log::info('Tech Tip updated by ' . $this->user, $techTip->toArray());
    }

    /**
     * Handle the TechTip "deleted" event.
     */
    public function deleted(TechTip $techTip): void
    {
        Log::info('Tech Tip deleted by ' . $this->user, $techTip->toArray());
    }

    /**
     * Handle the TechTip "restored" event.
     */
    public function restored(TechTip $techTip): void
    {
        Log::info('Tech Tip restored by ' . $this->user, $techTip->toArray());
    }

    /**
     * Queue files for deletion before the tip is deleted
     */
    public function forceDeleting(TechTip $techTip): void
    {
        $fileList = $techTip->Files->pluck('file_id')->toArray();

        if (count($fileList)) {
            foreach ($fileList as $fileId) {
                FileDataDeletedEvent::dispatch($fileId);
            }
        }
    }

    /**
     * Handle the TechTip "force deleted" event.
     */
    public function forceDeleted(TechTip $techTip): void
    {
        Log::info('Tech Tip trashed by ' . $this->user, $techTip->toArray());
    }
}
