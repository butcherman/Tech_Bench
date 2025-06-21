<?php

namespace App\Jobs\FileLink;

use App\Models\FileLink;
use App\Services\FileLink\FileLinkFileService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessLinkFilesJob implements ShouldQueue
{
    use Queueable;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(protected FileLink $link) {}

    /**
     * Cycle through all File Link files and verify that they are not in the
     * tmp folder.
     */
    public function handle(FileLinkFileService $svc): void
    {
        Log::debug('Starting Job - Process File Link Files Job for Link ID '
            .$this->link->link_id);

        $svc->checkLinkFileFolder($this->link);
        $svc->checkLinkFilePermission($this->link);
    }
}
