<?php

namespace App\Jobs\TechTip;

use App\Models\TechTip;
use App\Services\TechTip\TechTipFileService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessTipFilesJob implements ShouldQueue
{
    use Queueable;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(protected TechTip $techTip) {}

    /**
     * Execute the job.
     */
    public function handle(TechTipFileService $svc): void
    {
        Log::debug(
            'Starting Job - Process Tip Files Job for Tech Tip ID '
                . $this->techTip->tip_id
        );

        $svc->checkTipFileFolder($this->techTip);
    }
}
