<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PragmaRX\Version\Package\Version;
use ZanySoft\Zip\Zip;

/**
 * @codeCoverageIgnore
 */
class TBMaintenanceUpdateCommand extends Command
{
    protected $signature = 'tb_maintenance:update {--y|yes}';

    protected $description = 'Check for a newer Tech Bench version';

    /**
     * Create a new command instance
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command
     */
    public function handle()
    {
        $this->info('Checking for Updates');

        $verData = $this->getVersionData();
        $releaseVersion = $verData['tag_name'];
        $currentVersion = (new Version())->compact();

        //  Determine if the new version is higher than installed version
        $compare = version_compare($releaseVersion, $currentVersion);

        if ($compare < 1) {
            $this->info('You are running the most current version of Tech Bench');
            $this->info('No update necessary');

            return 0;
        }

        //  New version available - check to install
        $this->info('There is a newer version of Tech Bench available');

        if ($this->option('yes')) {
            $confirm = true;
        } else {
            $confirm = $this->confirm('Would you like to install it?');
        }

        if (! $confirm) {
            $this->info('Exiting...');

            return 0;
        }

        //  Download and apply the update
        $filename = $this->downloadRelease($verData['zipball_url'], $verData['tag_name']);
        $this->stageRelease($filename);

        $this->info('Update has been staged');
        $this->info('Please Reboot the tech_bench_app Container to apply the update');

        return 0;
    }

    /**
     * Get latest version information from github
     */
    protected function getVersionData()
    {
        $url = 'https://api.github.com/repos/butcherman/tech_bench/releases/latest';
        $response = Http::get($url);

        return $response;
    }

    /**
     * Download the latest release
     */
    protected function downloadRelease($url, $tag)
    {
        $filename = 'Tech_Bench_'.$tag.'.zip';

        $this->info('Downloading Tech Bench Version '.$tag);
        $this->info('Please wait...');

        $response = Http::get($url);
        Storage::disk('updates')->put($filename, $response);

        $this->info('Download Complete');

        return $filename;
    }

    /**
     * Prep the update by placing it in the staging folder
     * It will be applied on the next system reboot
     */
    protected function stageRelease($filename)
    {
        $this->info('Extracting Release');

        //  Verify the staging folder is empty and writable
        if (! is_writable('/staging')) {
            $this->error('Please add Write permissions to the /staging folder');

            return false;
        }
        $this->wipeStaging();

        //  Unzip release
        $root = config('filesystems.disks.updates.root');
        $archive = Zip::open($root.DIRECTORY_SEPARATOR.$filename);
        $archive->extract($root.DIRECTORY_SEPARATOR.'tmp');
        $archive->close();

        //  Move files to staging folder
        $directoryName = Storage::disk('updates')->directories('tmp')[0];
        $fileList = Storage::disk('updates')->allFiles($directoryName);

        $this->info('Staging Release');
        foreach ($fileList as $file) {
            $rename = str_replace($directoryName, '', $file);

            $info = pathinfo($rename);
            if (! File::isDirectory('/staging/'.$info['dirname'])) {
                File::makeDirectory('/staging/'.$info['dirname'], 0755, true);
            }

            File::move($root.DIRECTORY_SEPARATOR.$file, '/staging'.$rename);
        }

        //  Cleanup tmp files
        $this->info('Cleaning up...');
        File::deleteDirectory($root.DIRECTORY_SEPARATOR.'tmp');
    }

    /**
     * Remove all of the existing files in the staging folder
     */
    protected function wipeStaging()
    {
        $fileList = File::allFiles('/staging');
        foreach ($fileList as $file) {
            File::delete($file);
        }
    }
}
