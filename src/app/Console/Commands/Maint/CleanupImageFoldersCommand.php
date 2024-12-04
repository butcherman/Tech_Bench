<?php

namespace App\Console\Commands\Maint;

use App\Actions\File\CleanUploadedImages;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupImageFoldersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-image-folders {--read-only}';

    /**
     * The console command description
     *
     * @var string
     */
    protected $description = 'Remove any unused images from Logo and Uploaded Folders';

    /**
     * Execute the console command.
     */
    public function handle(CleanUploadedImages $action): void
    {
        Log::info('Starting Image Cleanup Command');
        $this->line('Checking for unused images');

        $res = $action(! $this->option('read-only'));

        $this->newLine();
        $this->info('Image Cleanup Process Completed');
        $this->line($res['logo_files'].' unused Logo Files found.');
        $this->line($res['upload_files'].' unused Uploaded Images found.');

        if (! $this->option('read-only')) {
            $this->info($res['total_files'].' deleted.');
        }
    }
}
