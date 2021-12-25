<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PragmaRX\Version\Package\Version;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Zip;
use Symfony\Component\Process\Process;

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

        // $this->openArchive();
        // $this->backupFiles();
        $this->backupDatabase();



    }

    /**
     * Create and open up the Archive Zip file
     */
    protected function openArchive()
    {
        //  Create a file that holds the current version of the Tech Bench application
        Storage::disk('backups')->put('version.txt', (new Version)->version_only());

        //  Create the backup Zip file
        $this->archive = Zip::create($this->diskLocal.$this->backupName);
        //  Add the version file
        $this->archive->add($this->diskLocal.'version.txt');
        //  Add the .env file that holds local global config
        $this->archive->add(base_path().DIRECTORY_SEPARATOR.'.env');
    }

    /**
     * Backup all of the Application Disks
     */
    protected function backupFiles()
    {
        if(!$this->files)
        {
            return false;
        }

        //  All uploaded files
        $this->archive->add(config('filesystems.disks.local.root'));
        //  All public uploaded files (images for Tech Tips and Logos)
        $this->archive->add(config('filesystems.disks.public.root'));
        //  All application logs
        $this->archive->add(config('filesystems.disks.logs.root'));
    }

    /**
     * Backup the MySQL Database
     */
    protected function backupDatabase()
    {
        if(!$this->database)
        {
            return false;
        }

        $processStr = ['mysqldump '.
            config('database.connections.mysql.database').' -u '.
            config('database.connections.mysql.username').' -p'.
            config('database.connections.mysql.password')];

        $process = new Process($processStr);

        try{

            $process->run();
        }
        catch(ProcessFailedException $e)
        {
            Log::critical('process failed');
            report($e);
        }

        $output = $process->getOutput();
        Log::critical($output);

        Storage::disk('backups')->put('database.sql', $output);
        // $this->archive->add($this->diskLocal.'database.sql');
    }
}
