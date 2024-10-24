<?php

namespace App\Jobs\Maintenance;

use App\Service\Maint\ImageFileCleanupService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ImageFileCleanupJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting Image File Cleanup Job');

        $service = new ImageFileCleanupService;
        $service->handle();

        Log::info('Image Cleanup Job Completed');
    }
}
