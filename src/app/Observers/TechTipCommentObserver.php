<?php

namespace App\Observers;

use App\Models\TechTipComment;

class TechTipCommentObserver extends Observer
{
    /**
     * Handle the TechTipComment "created" event.
     */
    public function created(TechTipComment $techTipComment): void
    {
        //
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
