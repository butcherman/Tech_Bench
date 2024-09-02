<?php

namespace App\Observers;

use App\Events\TechTips\TipCommentedEvent;
use App\Models\TechTipComment;
use Illuminate\Support\Facades\Log;

class TechTipCommentObserver
{
    protected $user;

    public function __construct()
    {
        $this->user = match (true) {
            request()->user() !== null => request()->user()->username,
            request()->ip() === '127.0.0.1' => 'Internal Service',
            default => request()->ip(),
        };
    }

    /**
     * Handle the TechTipComment "created" event.
     */
    public function created(TechTipComment $techTipComment): void
    {
        event(new TipCommentedEvent($techTipComment));

        Log::info(
            'New Tech Tip Comment created by '.$this->user,
            $techTipComment->toArray()
        );
    }

    /**
     * Handle the TechTipComment "updated" event.
     */
    public function updated(TechTipComment $techTipComment): void
    {
        Log::info(
            'A Tech Tip Comment has been updated by '.$this->user,
            $techTipComment->toArray()
        );
    }

    /**
     * Handle the TechTipComment "deleted" event.
     */
    public function deleted(TechTipComment $techTipComment): void
    {
        Log::notice(
            'Tech Tip Comment has been deleted by '.$this->user,
            $techTipComment->toArray()
        );
    }
}
