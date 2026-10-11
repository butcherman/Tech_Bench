<?php

namespace App\Observers;

use App\Models\TechTipComment;
use Illuminate\Support\Facades\Log;

class TechTipCommentObserver extends Observer
{
    /**
     * Handle the TechTipComment "created" event.
     */
    public function created(TechTipComment $techTipComment): void
    {
        Log::info(
            'New Tech Tip Comment created by '.$this->user.' for Tech Tip ID '.
                $techTipComment->tip_id,
            $techTipComment->toArray()
        );
    }

    /**
     * Handle the TechTipComment "updated" event.
     */
    public function updated(TechTipComment $techTipComment): void
    {
        Log::info(
            'Tech Tip Comment updated by '.$this->user.' for Tech Tip ID '.
                $techTipComment->tip_id,
            $techTipComment->toArray()
        );
    }

    /**
     * Handle the TechTipComment "deleted" event.
     */
    public function deleted(TechTipComment $techTipComment): void
    {
        Log::info(
            'Tech Tip Comment deleted by '.$this->user.' for Tech Tip ID '.
                $techTipComment->tip_id,
            $techTipComment->toArray()
        );
    }
}
