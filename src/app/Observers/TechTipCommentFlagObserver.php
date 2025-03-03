<?php

namespace App\Observers;

use App\Models\TechTipCommentFlag;
use Illuminate\Support\Facades\Log;

class TechTipCommentFlagObserver extends Observer
{
    /**
     * Handle the TechTipCommentFlag "created" event.
     */
    public function created(TechTipCommentFlag $techTipCommentFlag): void
    {
        Log::notice(
            'A Tech Tip Comment was flagged for review by '.$this->user,
            $techTipCommentFlag->TechTipComment->toArray()
        );
    }

    /**
     * Handle the TechTipCommentFlag "deleted" event.
     */
    public function deleted(TechTipCommentFlag $techTipCommentFlag): void
    {
        Log::notice(
            'A Tech Tip Flag was deleted by '.$this->user,
            $techTipCommentFlag->TechTipComment->toArray()
        );
    }
}
