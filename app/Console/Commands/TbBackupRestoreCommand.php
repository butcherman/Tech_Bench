<?php

namespace App\Console\Commands;

use Zip;
use PragmaRX\Version\Package\Version;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;

class TbBackupRestoreCommand extends Command
{
    protected $signature   = 'tb_backup:restore {filename?} {--confirmed}';
    protected $description = 'Restore the Tech Bench from a previously saved backup';
    protected $filename;
    protected $basename;

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
        $this->newLine();
        $this->info('Starting Tech Bench Restore');

        //  If there was not a filename supplied, give the choice of what file to restore
        if(is_null($this->argument('filename')))
        {
            if(!$this->assignBackupFile())
            {
                return 0;
            }
        }
        else
        {
            $this->filename = $this->argument('filename');
        }

        //  Verify that the backup file exists
        if(!Storage::disk('backups')->exists($this->filename))
        {
            $this->error('     The filename entered does not exist                                           ');
            $this->error('                  Exiting...                                                       ');
            return 0;
        }

        //  Verify a proper backup file
        if(!$this->validateFile())
        {
            $this->error('     The backup file specified is not a Tech Bench Backup                          ');
            $this->error('                  Exiting...                                                       ');
            return 0;
        }

        if(!$this->checkVersion())
        {
            $this->cleanup();
            return 0;
        }

        //  Verify that the user wants to run this process
        if(!$this->option('confirmed'))
        {
            $this->warn(' ___________________________________________________________________ ');
            $this->warn('|                      IMPORTANT NOTE:                              |');
            $this->warn('|   ALL EXISTING DATA WILL BE ERASED AND REPLACED WITH THE BACKUP   |');
            $this->warn('|___________________________________________________________________|');
            $this->warn('                                                                     ');
        }

        if(!$this->option('confirmed') && !$this->confirm('Are you sure?'))
        {
            $this->cleanup();
            $this->line('Operation Canceled');
            return 0;
        }

        //  Start the restore process
        Log::critical('Restoring backup from filename - '.$this->filename);
        $this->newLine();
        $this->warn('Restoring backup from filename - '.$this->filename);

        $this->call('down');
        $this->newLine();
        if(!$this->loadDatabase())
        {
            $this->error('     Unable to modify database structure                                             ');
            $this->error('     Please verify that the database user in the .env file has write permissions     ');
            $this->error('     Exiting....                                                                     ');
            $this->cleanup();
            return 0;
        }
        $this->loadFiles();

        // $this->cleanup();
        $this->newLine();
        $this->info('Tech Bench has been restored');
        $this->call('up');
        return 0;
    }

    /**
     * If no filename was specified, give user a list of available files to select from
     */
    protected function assignBackupFile()
    {
        $backupList = Storage::disk('backups')->files();
        $backups    = ['Cancel'];
        foreach($backupList as $b)
        {
            $parts = pathinfo($b);
            if($parts['extension'] === 'zip')
            {
                $backups[] = $b;
            }
        }

        $this->line('Please select which backup file to restore');
        $choice = $this->choice('Select Number:', $backups);

        if($choice === 'Cancel')
        {
            $this->line('Canceling...');
            return false;
        }

        $this->filename = $choice;
        return true;
    }

    /**
     * Verify that the file is in fact a valid Tech Bench backup file (to the best of our abilities)
     */
    protected function validateFile()
    {
        $this->line('Checking backup file');

        //  This must be a .zip file
        $fileParts = pathinfo($this->filename);
        $this->basename = $fileParts['filename'].DIRECTORY_SEPARATOR;
        if($fileParts['extension'] !== 'zip')
        {
            return false;
        }

        //  Open and extract the archive file
        $archive = Zip::open(config('filesystems.disks.backups.root').DIRECTORY_SEPARATOR.$this->filename);
        $archive->extract(config('filesystems.disks.backups.root').DIRECTORY_SEPARATOR.$this->basename);
        $archive->close();

        //  Make sure that the version, .env, and module files are there
        if(Storage::disk('backups')->missing($this->basename.'.env')
                    || Storage::disk('backups')->missing($this->basename.'modules.txt')
                    || Storage::disk('backups')->missing($this->basename.'version.txt'))
        {
            return false;
        }

        //  Verify that the .env file is writeable
        if(!is_writable(base_path().DIRECTORY_SEPARATOR.'.env'))
        {
            return false;
        }

        return true;
    }

    /**
     * Remove any files created by this process
     */
    protected function cleanup()
    {
        $this->line('Cleaning up...');
        Storage::disk('backups')->deleteDirectory($this->basename);
    }

    /**
     * Make sure that the Tech Bench backup is the same version as the Tech Bench
     */
    protected function checkVersion()
    {
        //  Backup file version
        $verText = Storage::disk('backups')->get($this->basename.DIRECTORY_SEPARATOR.'version.txt');
        $verArr  = explode(' ', $verText);
        $bkVer   = floatval($verArr[1]);
        //  Tech Bench Application version
        $verObj = new Version;
        $appVer = floatval($verObj->major().'.'.$verObj->minor());

        if($appVer < $bkVer)
        {
            $this->newLine();
            $this->error('|     This Backup is from a newer version of Tech Bench                              |');
            $this->error('|     Please install the version '.$bkVer.' before trying to restore this backup            |');
            $this->error('|     Exiting...                                                                     |');
            $this->newLine();
            return false;
        }

        return true;
    }

    /**
     * Load the files from the backup
     */
    protected function loadFiles()
    {
        //  Start with the .env file
        $env = Storage::disk('backups')->get($this->basename.'.env');
        File::put(base_path().DIRECTORY_SEPARATOR.'.env', $env);

        //  Load application files if they are part of the backup
        if(Storage::disk('backups')->exists($this->basename.'app'))
        {
            $this->line('Restoring files');
            $this->wipeDisk('local');
            $this->copyDisk('local');
        }

    }

    /**
     * Load the database from the backup
     */
    protected function loadDatabase()
    {
        if(Storage::disk('backups')->exists($this->basename.'backup.sql'))
        {
            $this->line('Restoring database');
            try{

                DB::connection(DB::getDefaultConnection())
                ->getSchemaBuilder()
                ->dropAllTables();
                DB::reconnect();
            }
            catch(QueryException $e)
            {
                report($e);
                return false;
            }

            //  Input the database information one line at a time
            $dbFile = file(Storage::disk('backups')->path($this->basename.'backup.sql'));
            foreach($dbFile as $line)
            {
                DB::unprepared(str_replace('\r\n', '', $line));
            }

            //  Run any migrations in case the application is newer than the backup
            $this->callSilently('migrate');

            return true;
        }
    }

    /**
     * Copy all of the files from a folder into a disk instance
     */
    protected function copyDisk($disk)
    {
        //  Get the root folder name
        $folder = config('filesystems.disks.'.$disk.'.base_folder');
        $files  = Storage::disk('backups')->allFiles($this->basename.$folder);

        foreach($files as $file)
        {
            $data    = Storage::disk('backups')->get($file);
            //  Trim the file path to the correct new path
            //  If this is a Windows server, the directory separator will be incorrect
            $rename = str_replace(str_replace('\\', '/', $this->basename).$folder, '', $file);

            Storage::disk($disk)->put($rename, $data);
        }

        //  Make sure that the symbolic link for the public folder exists
        $this->callSilently('storage:link');
    }

    /**
     * Wipe a directory along with all sub-directories
     */
    protected function wipeDisk($disk)
    {
        //  Clear all files from the disk
        $files = Storage::disk($disk)->allFiles();

        foreach($files as $file)
        {
            if($file != '.gitignore')
            {
                Storage::disk($disk)->delete($file);
            }
        }

        //  Clear all sub directories from the disk
        $folders = Storage::disk($disk)->directories();
        foreach($folders as $folder)
        {
            Storage::disk($disk)->deleteDirectory($folder);
        }
    }
}
