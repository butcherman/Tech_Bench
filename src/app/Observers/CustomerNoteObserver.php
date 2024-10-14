<?php

namespace App\Observers;

use App\Models\CustomerNote;
use Illuminate\Support\Facades\Log;

class CustomerNoteObserver
{
    /** @var User|string */
    protected $user;

    public function __construct()
    {
        $this->user = match (true) {
            ! is_null(request()->user()) => request()->user()->username,
            request()->ip() === '127.0.0.1' => 'Internal Service',
            // @codeCoverageIgnoreStart
            default => request()->ip(),
            // @codeCoverageIgnoreEnd
        };
    }

    public function created(CustomerNote $note): void
    {
        Log::info(
            'New Customer Note created for '.$note->Customer->name.
                ' by '.$this->user,
            $note->toArray()
        );
    }

    public function updated(CustomerNote $note): void
    {
        Log::info(
            'Customer Note for '.$note->Customer->name.
                ' updated by '.$this->user,
            $note->toArray()
        );
    }

    public function deleted(CustomerNote $note): void
    {
        Log::notice(
            'Customer Note for '.$note->Customer->name.
                ' deleted by '.$this->user,
            $note->toArray()
        );
    }

    public function restored(CustomerNote $note): void
    {
        Log::info(
            'Customer Note restored for '.$note->Customer->name.' by '.$this->user,
            $note->toArray()
        );
    }

    public function forceDeleted(CustomerNote $note): void
    {
        Log::notice(
            'Customer NOte force deleted for '.$note->Customer->name.
                ' by '.$this->user,
            $note->toArray()
        );
    }
}
