<?php

namespace App\Console\Commands\Maint;

use App\Actions\Maintenance\CleanImageFolders;
use Illuminate\Console\Command;

use function Laravel\Prompts\spin;

class CleanImageFoldersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-image-folders {--fix}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove any unused images from Logo and Uploaded Folders';

    /**
     * Execute the console command.
     */
    public function handle(CleanImageFolders $svc): void
    {
        $this->line('Image Cleanup Process Started');

        $res = spin(
            message: 'Checking for orphaned image files',
            callback: fn () => $svc($this->option('fix'))
        );

        $this->info('Found '.$res.' orphaned files');
        if ($this->option('fix')) {
            $this->info('Orphaned images removed');
        }

        $this->line('Image Cleanup Process Completed');
    }
}
