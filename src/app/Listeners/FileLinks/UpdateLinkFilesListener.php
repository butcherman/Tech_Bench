<?php

namespace App\Listeners\FileLinks;

use App\Events\FileLinks\FileLinkEvent;
use App\Service\HandleFileService;
use Illuminate\Support\Facades\Log;

class UpdateLinkFilesListener
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
    public function handle(FileLinkEvent $event): void
    {
        Log::debug('Starting Update Link Files Listener');

        $service = new HandleFileService;

        $fileList = $event->link->FileUpload;

        $service->moveTmpFile('fileLinks', $event->link->link_id, $fileList);
        $service->setPublicFlag($fileList, true);
    }
}
