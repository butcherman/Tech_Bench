<?php

namespace App\Console\Commands;

use Zip;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class backupRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tb-backup:run
                                {--databaseOnly : Only backup configuration database}
                                {--filesOnly : Only backup uploaded files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a Tech Bench Backup';

    protected $archive, $bar;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Storage::disk('backup')->put('.ignore', '');
        $this->line('Creating Tech Bench Backup...');
        $this->bar = $this->output->createProgressBar(12);

        $this->createBackupArchive();

        if(!$this->option('databaseOnly'))
        {
            $this->line('');
            $this->line('Backing up files');
            $this->line('This may take some time');
            $this->backupFiles();
        }
        if(!$this->option('filesOnly'))
        {
            $this->backupDatabase();
        }

        $this->closeBackupArchive();
        $this->cleanup();
        $this->bar->finish();
        $this->line('');
        $this->info('Backup Completed');
    }


    protected function createBackupArchive()
    {
        $backupName = 'TB_Backup_'.Carbon::now()->format('Ymd_his').'.zip';

        //  Create a text file holding the current system version
        $version = new \PragmaRX\Version\Package\Version();
        File::put(config('filesystems.disks.backup.root').DIRECTORY_SEPARATOR.'version.txt', $version->version_only());
        $this->bar->advance();

        //  Create the archive and place the version file in it
        $this->zip = Zip::create(config('filesystems.disks.backup.root').DIRECTORY_SEPARATOR.$backupName);
        $this->bar->advance();
        $this->zip->add(config('filesystems.disks.backup.root').DIRECTORY_SEPARATOR.'version.txt');
        $this->bar->advance();
        $this->zip->add(base_path().DIRECTORY_SEPARATOR.'.env');
        $this->bar->advance();
    }

    protected function closeBackupArchive()
    {
        $this->zip->close();
        $this->bar->advance();
    }

    //  Backup stored files
    protected function backupFiles()
    {
        //  All uploaded files
        $this->zip->add(config('filesystems.disks.local.root'));
        $this->bar->advance();
        //  All publicly available files
        $this->zip->add(config('filesystems.disks.public.root'));
        $this->bar->advance();
        //  All system logs
        $this->zip->add(config('filesystems.disks.logs.root'));
        $this->bar->advance();
    }

    //  Create a MYSQLDUMP of the database
    protected function backupDatabase()
    {
        $processStr = 'mysqldump '.
            config('database.connections.mysql.database').' -u '.
            config('database.connections.mysql.user').' -p'.
            config('database.connections.mysql.password');
        //  TODO - should this be an array??
        $process = new Process(/** @scrutinizer ignore-type */$processStr);
        $process->run();

        File::put(config('filesystems.disks.backup.root').DIRECTORY_SEPARATOR.'database.sql', $process->getOutput());
        $this->bar->advance();
        $this->zip->add(config('filesystems.disks.backup.root').DIRECTORY_SEPARATOR.'database.sql');
        $this->bar->advance();
    }

    //  Remove any temporary files
    protected function cleanup()
    {
        $this->bar->advance();
        Storage::disk('backup')->delete('version.txt');
        Storage::disk('backup')->delete('database.sql');
    }
}
