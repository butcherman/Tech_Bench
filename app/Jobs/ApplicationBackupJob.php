<?php

namespace App\Jobs;

use Zip;
use Carbon\Carbon;
use Nwidart\Modules\Facades\Module;
use PragmaRX\Version\Package\Version;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplicationBackupJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use SerializesModels;
    use InteractsWithQueue;

    protected $files;
    protected $archive;
    protected $database;
    protected $diskLocal;
    protected $backupName;

    /**
     * Create a new job instance
     */
    public function __construct($database = true, $files = true)
    {
        $this->database   = $database;
        $this->files      = $files;
        $this->backupName = 'TB_Backup_'.Carbon::now()->format('Ymd_his').'.zip';
        $this->diskLocal  = config('filesystems.disks.backups.root').DIRECTORY_SEPARATOR;
    }

    /**
     * Execute the job
     */
    public function handle()
    {
        Log::notice('Starting Application Backup', [
            'Backup Database' => $this->database,
            'Backup Files'    => $this->files
        ]);

        $this->openArchive();
        $this->backupFiles();
        $this->backupDatabase();
        $this->closeArchive();
        $this->cleanup();

        Log::notice('Backup Completed Successfully - file located at '.$this->diskLocal.$this->backupName);
    }

    /**
     * Create and open up the Archive Zip file
     */
    protected function openArchive()
    {
        //  Create a file that holds the current version of the Tech Bench application
        Log::debug('Writing version file');
        Storage::disk('backups')->put('version.txt', (new Version)->version_only());
        //  Create a file that holds all enabled modules
        Log::debug('Writing modules file');
        Storage::disk('backups')->put('modules.txt', Module::all());

        //  Create the backup Zip file
        Log::debug('Creating archive file - '.$this->diskLocal.$this->backupName);
        $this->archive = Zip::create($this->diskLocal.$this->backupName);
        //  Add the version and module files
        Log::debug('Adding version and modules file to archive');
        $this->archive->add($this->diskLocal.'version.txt');
        $this->archive->add($this->diskLocal.'modules.txt');
        //  Add the .env file that holds local global config
        Log::debug('Adding env file to archive');
        $this->archive->add(base_path().DIRECTORY_SEPARATOR.'.env');
    }

    /**
     * Close and finish the Archive Zip File
     */
    protected function closeArchive()
    {
        Log::debug('Closing archive');
        $this->archive->close();
    }

    /**
     * Backup all of the Application Disks
     */
    protected function backupFiles()
    {
        if(!$this->files)
        {
            Log::debug('Skipping file system backup');
            return false;
        }

        //  All uploaded files
        Log::debug('Adding Local Disk to archive');
        $this->archive->add(config('filesystems.disks.local.root'));
        //  All public uploaded files (images for Tech Tips and Logos)
        Log::debug('Adding Public Disk to archive');
        $this->archive->add(config('filesystems.disks.public.root'));
        //  All application logs
        Log::debug('Adding all log files to archive');
        $this->archive->add(config('filesystems.disks.logs.root'));
    }

    /**
     * Backup the MySQL Database
     */
    protected function backupDatabase()
    {
        if(!$this->database)
        {
            Log::debug('Skipping Database backup');
            return false;
        }

        Log::Debug('Adding Database to archive');
        Artisan::call('db:masked-dump', ['output' => $this->diskLocal.'backup.sql']);
        $this->archive->add($this->diskLocal.'backup.sql');
    }

    /**
     * Remove any temporary files
     */
    protected function cleanup()
    {
        Log::debug('Cleaning Up');
        Storage::disk('backups')->delete('version.txt');
        Storage::disk('backups')->delete('backup.sql');
        Storage::disk('backups')->delete('modules.txt');
    }
}
