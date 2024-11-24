<?php

namespace App\Observers;

use App\Models\TechTipCommentFlag;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TechTipCommentFlagObserver extends Observer
{
    /**
     * Handle the TechTipCommentFlag "created" event.
     */
    public function created(TechTipCommentFlag $techTipCommentFlag): void
    {
        //
    }

    /**
     * Handle the TechTipCommentFlag "updated" event.
     */
    public function updated(TechTipCommentFlag $techTipCommentFlag): void
    {
        //
    }

    /**
     * Handle the TechTipCommentFlag "deleted" event.
     */
    public function deleted(TechTipCommentFlag $techTipCommentFlag): void
    {
        //
    }

    /**
     * Handle the TechTipCommentFlag "restored" event.
     */
    public function restored(TechTipCommentFlag $techTipCommentFlag): void
    {
        //
    }

    /**
     * Handle the TechTipCommentFlag "force deleted" event.
     */
    public function forceDeleted(TechTipCommentFlag $techTipCommentFlag): void
    {
        //
    }
}
