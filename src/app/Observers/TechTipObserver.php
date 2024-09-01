<?php

namespace App\Observers;

use App\Events\File\FileDataDeletedEvent;
use App\Models\TechTip;
use App\Models\TechTipView;
use Illuminate\Support\Facades\Log;
use SebastianBergmann\Type\VoidType;

class TechTipObserver
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
     * Actions for when a Tech Tip is retrieved from the database
     */
    public function retrieved(TechTip $techTip)
    {
        // Separate log entry for Search Results
        if (
            request()->routeIs('tech-tips.search') ||
            request()->routeIs('publicTips.search')
        ) {
            Log::debug('Tech Tip retrieved as part of search result', [
                'tip_id' => $techTip->tip_id,
                'user' => $this->user,
            ]);
        } else if (request()->route() !== null && !request()->routeIs('dashboard')) {
            // We do not log a tip being retrieved as part of a queued job
            Log::debug('Tech Tip being viewed', [
                'tip_id' => $techTip->tip_id,
                'user' => $this->user,
            ]);


        }
    }

    /**
     * Handle the TechTip "created" event.
     */
    public function created(TechTip $techTip): void
    {
        Log::info(
            'New Tech Tip created by ' . $this->user,
            $techTip->toArray()
        );

        // Create Counter Entry to monitor views
        TechTipView::create(['tip_id' => $techTip->tip_id]);
    }

    /**
     * Handle the TechTip "updated" event.
     */
    public function updated(TechTip $techTip): void
    {
        Log::info(
            'Tech Tip has been updated by ' . $this->user,
            $techTip->toArray()
        );
    }

    /**
     * Handle the TechTip "deleted" event.
     */
    public function deleted(TechTip $techTip): void
    {
        Log::notice(
            'Tech Tip has been disabled by ' . $this->user,
            [
                'tip_id' => $techTip->tip_id,
                'subject' => $techTip->subject,
            ]
        );
    }

    /**
     * Handle the TechTip "restored" event.
     */
    public function restored(TechTip $techTip): void
    {
        Log::notice(
            'A Disabled Tech Tip has been restored by ' . $this->user,
            [
                'tip_id' => $techTip->tip_id,
                'subject' => $techTip->subject,
            ]
        );
    }

    /**
     * Handle the TechTip "force deleted" event.
     */
    public function forceDeleting(TechTip $techTip): void
    {
        // Get any Files attached to tip
        $fileList = $techTip->FileUpload->pluck('file_id')->toArray();

        foreach ($fileList as $fileId) {
            event(new FileDataDeletedEvent($fileId));
        }
    }

    public function forceDeleted(TechTip $techTip): void
    {
        Log::notice(
            'A Tech Tip has been deleted by ' . $this->user,
            [
                'tip_id' => $techTip->tip_id,
                'subject' => $techTip->subject,
            ]
        );
    }
}
