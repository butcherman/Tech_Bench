<?php

namespace App\Listeners\TechTips;

use App\Events\TechTips\TechTipEvent;
use App\Service\HandleFileService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateTipFilesListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TechTipEvent $event): void
    {
        Log::debug('Starting Update Tip Files Listener');
        $service = new HandleFileService();

        $tipFiles = $event->techTip->FileUpload;

        $service->moveTmpFile('tips', $event->techTip->tip_id, $tipFiles);
    }
}
