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
        Log::info(
            'A Tech Tip Comment has been updated by ' . request()->user()->username,
            $techTipComment->toArray()
        );
    }

    /**
     * Handle the TechTipComment "deleted" event.
     */
    public function deleted(TechTipComment $techTipComment): void
    {
        Log::notice(
            'Tech Tip Comment has been deleted by ' . request()->user()->username,
            $techTipComment->toArray()
        );
    }
}
