<?php

namespace App\Jobs;

use App\Models\TechTip;
use App\Models\UserTechTipBookmark;
use App\Models\UserTechTipRecent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TechTipsRemoveBookmarksJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use SerializesModels;
    use InteractsWithQueue;

    protected $tip;

    /**
     * Create a new job instance
     */
    public function __construct(TechTip $tip)
    {
        $this->tip = $tip;
    }

    /**
     * Remove a Tech Tip from all users bookmarks and recent lists
     */
    public function handle()
    {
        //  Remove the tip from any users 'recent' list
        UserTechTipRecent::where('tip_id', $tip->tip_id)->delete();
        //  Remove the tip from any users 'bookmark' list
        UserTechTipBookmark::where('tip_id', $tip->tip_id)->delete();

        Log::debug('Remove Tech Tip - '.$this->tip->subject.' from all users bookmarks and recent list');
    }
}
