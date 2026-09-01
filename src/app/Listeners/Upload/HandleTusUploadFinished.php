<?php

namespace App\Listeners\Upload;

use App\Services\Admin\ApplicationSettingsService;
use ArthurPatriot\Tus\Events\FileUploadFinished;

class HandleTusUploadFinished
{
    /**
     * Create the event listener.
     */
    public function __construct(protected ApplicationSettingsService $svc) {}

    /**
     * Handle the event.
     */
    public function handle(FileUploadFinished $event): void
    {
        $tusFile = $event->tusFile;

        if (($tusFile->metadata['purpose'] ?? null) !== 'logo') {
            return;
        }

        $this->svc->updateLogo($tusFile);
    }
}
