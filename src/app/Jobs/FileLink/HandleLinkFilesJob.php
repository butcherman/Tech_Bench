<?php

namespace App\Jobs\FileLink;

use App\Models\FileLink;
use App\Service\FileLink\FileLinkFileService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class HandleLinkFilesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected FileLink $link) {}

    /**
     * Execute the job.
     */
    public function handle(FileLinkFileService $svc): void
    {
        $svc->moveTmpFiles($this->link);
    }
}
