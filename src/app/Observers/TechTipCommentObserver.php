<?php

namespace App\Observers;

use App\Events\TechTips\TipCommentedEvent;
use App\Models\TechTipComment;
use Illuminate\Support\Facades\Log;

class TechTipCommentObserver
{
    /**
     * Handle the TechTipComment "created" event.
     */
    public function created(TechTipComment $techTipComment): void
    {
        event(new TipCommentedEvent($techTipComment));

        Log::info(
            'New Tech Tip Comment created by ' . request()->user()->username,
            $techTipComment->toArray()
        );
    }

    /**
     * Handle the TechTipComment "updated" event.
     */
    public function updated(TechTipComment $techTipComment): void
    {
        //
    }

    /**
     * Handle the TechTipComment "deleted" event.
     */
    public function deleted(TechTipComment $techTipComment): void
    {
        //
    }

    /**
     * Handle the TechTipComment "restored" event.
     */
    public function restored(TechTipComment $techTipComment): void
    {
        //
    }

    /**
     * Handle the TechTipComment "force deleted" event.
     */
    public function forceDeleted(TechTipComment $techTipComment): void
    {
        //
    }
}
