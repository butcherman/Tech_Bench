<?php

// TODO - Refactor

namespace App\Console\Commands\Maint;

use App\Service\Maint\ImageFileCleanupService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupImageFoldersCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:cleanup-image-folders {--read-only}';

    /**
     * The console command description
     */
    protected $description = 'Remove any unused images from Logo and Uploaded Folders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Starting Image Cleanup Process');

        $service = new ImageFileCleanupService($this->option('read-only'));
        $service->handle();

        $this->newLine();
        $this->info('Found '.$service->getFileCount().' orphaned image files.');
        $this->newLine();

        if ($this->option('read-only')) {
            $this->info('File List: ');
            foreach ($service->getFileList() as $fileData) {
                $this->line('    '.$fileData);
            }
        } else {
            $this->info('Deleted '.$service->getFileCount().' files');
        }

        $this->newLine();
        $this->info('Image Cleanup Process Completed');
    }
}
