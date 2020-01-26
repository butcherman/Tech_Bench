<?php

namespace App\Console\Commands;

use Zip;
use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use Illuminate\Support\Facades\DB;

class backupRestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tb-backup:restore {filename} {--confirmed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore application from a previous backup';
    protected $archive, $bar, $baseName;

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
        if(!Storage::disk('backup')->exists($this->argument('filename')))
        {
            $this->error('The backup filename you entered does not exist.');
            $this->error('Exiting                                        ');
            return 1;
        }

        if(!$this->option('confirmed'))
        {
            $this->question('____________________________________________________________');
            $this->question('|                  IMPORTANT NOTE:                         |');
            $this->question('|   ALL DATA WILL BE ERASED AND REPLACED WITH THE BACKUP   |');
            $this->question('|__________________________________________________________|');
        }

        if($this->option('confirmed') || $this->confirm('Are You Sure?'))
        {
            $this->line('Restoring Backup.  Please Wait');
            $this->line('This could take some time');
            $this->bar = $this->output->createProgressBar(10);
            $this->call('down');
            $this->prepareBackup();
            $this->checkVersion();
            $this->replaceFiles();
            $this->wipeDatabase();
            $this->loadDatabase();
            $this->cleanup();
            $this->bar->finish();
            $this->line('');
            $this->call('up');
            $this->line('Restore Completed');
        }

        return 1;
    }

    //  Open the zip and prepare it for restore
    protected function prepareBackup()
    {
        $this->bar->advance();
        $fileParts = pathinfo($this->argument('filename'));
        $this->baseName = $fileParts['filename'];
        $this->archive = Zip::open(config('filesystems.disks.backup.root') . DIRECTORY_SEPARATOR.$this->argument('filename'));
        // if (!$this->archive->has('version.txt')) {
        //     $this->error('THIS IS NOT A VALID TECH BENCH BACKUP');
        //     $this->error('Exiting');
        //     return 1;
        // }

        $this->archive->extract(config('filesystems.disks.backup.root').DIRECTORY_SEPARATOR.$this->baseName);
        $this->bar->advance();
    }

    protected function cleanup()
    {
        Storage::disk('backup')->deleteDirectory($this->baseName);
    }

    //  Check to verify that the version matches
    protected function checkVersion()
    {
        $backupVer = Storage::disk('backup')->get($this->baseName.DIRECTORY_SEPARATOR.'version.txt');
        $appVer = new \PragmaRX\Version\Package\Version();
        $this->bar->advance();

        if($backupVer !== $appVer->version_only())
        {
            $this->error('Unable to Restore, you are running '.$appVer->version());
            $this->error('This backup is for version '.$backupVer);
            $this->error('Please load the proper version before loading this backup');

            $this->cleanup();
            return 1;
        }
    }

    protected function clearAllFiles($disk)
    {
        $files = Storage::disk($disk)->files();
        foreach($files as $file)
        {
            if($file != '.gitignore')
            {
                Storage::disk($disk)->delete($file);
            }
        }
        $folders = Storage::disk($disk)->directories();
        foreach($folders as $folder)
        {
            Storage::disk($disk)->deleteDirectory($folder);
        }
    }

    protected function copyAllFiles($backupFolder, $disk)
    {
        $files = Storage::disk('backup')->allFiles($this->baseName.DIRECTORY_SEPARATOR.$backupFolder);

        foreach($files as $file)
        {
            $newFile = str_replace($this->baseName.'/'.$backupFolder.'/', '', $file);
            Storage::disk($disk)->put($newFile, Storage::disk('backup')->get($file));
        }
    }

    //  Replace the system files
    protected function replaceFiles()
    {
        $this->bar->advance();
        //  Start with the .env file
        $env = Storage::disk('backup')->get($this->baseName.DIRECTORY_SEPARATOR.'.env');
        File::put(base_path().DIRECTORY_SEPARATOR.'.env', $env);
        $this->bar->advance();
        //  Replace the log files
        $this->clearAllFiles('logs');
        $this->copyAllFiles('logs', 'logs');
        $this->bar->advance();
        //  Replace the public files
        $this->clearAllFiles('public');
        $this->copyAllFiles('public', 'public');
        $this->bar->advance();
        //  Replace all upladed files
        $this->clearAllFiles('local');
        $this->copyAllFiles('files', 'local');
        $this->bar->advance();
    }

    //  Clear out current database
    protected function wipeDatabase()
    {
        DB::connection(DB::getDefaultConnection())
            ->getSchemaBuilder()
            ->dropAllTables();
        DB::reconnect();
        $this->bar->advance();
    }

    //  Load the backed up database
    protected function loadDatabase()
    {
        $dbContents = Storage::disk('backup')->get($this->baseName.DIRECTORY_SEPARATOR.'database.sql');
        DB::unprepared($dbContents);
        $this->bar->advance();
    }
}
