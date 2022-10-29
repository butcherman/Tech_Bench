<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Nwidart\Modules\Facades\Module;
use PragmaRX\Version\Package\Version;
use ZanySoft\Zip\Zip;

/**
 * @codeCoverageIgnore
 */
class TbMaintenanceRestoreCommand extends Command
{
    protected $signature   = 'tb_maintenance:restore
                                    {filename?   : Name of backup file}
                                    {--confirmed : Bypass confirmation prompt}';
    protected $description = 'Restore the Tech Bench from a previously saved backup';

    protected $filename;
    protected $basename;

    /**
     * Execute the console command
     */
    public function handle()
    {
        $this->newLine();
        $this->info('Starting Tech Bench Restore');
        Log::notice('Backup Restore Command called');

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
            Log::debug('Log file given as argument - '.$this->argument('filename'));
        }

        //  Verify that the backup file exists
        if(!Storage::disk('backups')->exists($this->filename))
        {
            $this->error('     The filename entered does not exist                                           ');
            $this->error('                  Exiting...                                                       ');
            Log::error('Backup specified to restore does not exist.  Looking for '.$this->filename);
            return 0;
        }

        //  Verify a proper backup file
        if(!$this->validateFile())
        {
            $this->error('     The backup file specified is not a Tech Bench Backup                          ');
            $this->error('                  Exiting...                                                       ');
            $this->cleanup();
            return 0;
        }

        if(!$this->checkVersion())
        {
            $this->cleanup();
            return 0;
        }

        if(!$this->checkForModules())
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
            Log::info('Backup Restore operation canceled');
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
            $this->call('up');
            return 0;
        }
        $this->loadFiles();

        $this->cleanup();
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
                $backups[] = $b; // parts['filename'];
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
        Log::info('Backup Filename selected for restore - '.$choice);
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
            Log::alert('Trying to restore a file that is not a zip file', $fileParts);
            return false;
        }

        //  Open and extract the archive file
        $archive = Zip::open(config('filesystems.disks.backups.root').DIRECTORY_SEPARATOR.$this->filename);
        $archive->extract(config('filesystems.disks.backups.root').DIRECTORY_SEPARATOR.$this->basename);
        $archive->close();

        //  Make sure that the version, .env, and module files are there
        if(Storage::disk('backups')->missing($this->basename.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'.env')
                    || Storage::disk('backups')->missing($this->basename.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'modules_statuses.json')
                    || Storage::disk('backups')->missing($this->basename.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'version.txt'))
        {

            Log::alert('Selected backup is missing one or more esential files', [
                '.env'                  => Storage::disk('backups')->exists($this->basename.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'.env'),
                'modules_statuses.json' => Storage::disk('backups')->exists($this->basename.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'modules_statuses.json'),
                'version.txt'           => Storage::disk('backups')->exists($this->basename.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'version.txt')
            ]);
            return false;
        }

        //  Verify that the .env file is writeable
        if(!is_writable(base_path().DIRECTORY_SEPARATOR.'.env'))
        {
            Log::alert('The Tech Bench base path is not writable.  Unable to restore backup');
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
        Log::debug('Cleaning up after restore process, deleting directory - '.$this->basename);
        Storage::disk('backups')->deleteDirectory($this->basename);
    }

    /**
     * Make sure that the Tech Bench backup is the same version as the Tech Bench
     */
    protected function checkVersion()
    {
        //  Backup file version
        $verText = Storage::disk('backups')->get($this->basename.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'version.txt');
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
            Log::alert('Backup selected to restore is from Tech Bench version '.$bkVer.'.  Unable to complete restore process');
            return false;
        }

        return true;
    }

    /**
     * Check to see if the backup file contained any module data and verify if those modules are installed or not
     */
    protected function checkForModules()
    {
        $moduleFile = json_decode(Storage::disk('backups')->get($this->basename.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'modules_statuses.json'));
        $missing    = [];

        foreach($moduleFile as $module => $enabled)
        {
            $isInstalled = Module::find($module);
            if(!$isInstalled && $enabled)
            {
                $missing[] = $module;
            }
        }

        if($missing)
        {
            $this->warn('There is one or more Modules as part of the backup that have not been installed on this Tech Bench');
            $this->warn('If you plan to use these Modules, it is highly recommended to install them before applying the backup');
            $this->warn('The missing Modules are');
            $this->newLine();
            $this->line($missing);
            // $continue = $this->confirm('Do you with to continue?');

            if($this->confirm('Do you wish to continue?'))
            {
                return true;
            }

            return false;
        }

        // dd($missing);
        return true;
    }

    /**
     * Load the files from the backup
     */
    protected function loadFiles()
    {
        //  Start with the .env file
        $env = Storage::disk('backups')->get($this->basename.'app'.DIRECTORY_SEPARATOR.'.env');
        File::put(base_path().DIRECTORY_SEPARATOR.'.env', $env);
        Log::debug('Restored .env file');

        //  Load application files if they are part of the backup
        if(Storage::disk('backups')->exists($this->basename.'app'.DIRECTORY_SEPARATOR.'storage'))
        {
            $this->line('Restoring files');
            Log::debug('Restoring file system');
            $this->wipeDisk('local');
            $this->copyDisk('local');
        }

    }

    /**
     * Load the database from the backup
     */
    protected function loadDatabase()
    {
        if(Storage::disk('backups')->exists($this->basename.'db-dumps'.DIRECTORY_SEPARATOR.'mysql-tech-bench.sql'))
        {
            $this->line('Restoring database');
            Log::debug('Restoring database');

            try
            {

                DB::connection(DB::getDefaultConnection())
                    ->getSchemaBuilder()
                    ->dropAllTables();
                DB::reconnect();
            }
            catch(QueryException $e)
            {
                report($e);
                Log::critical($e);
                return false;
            }

            //  Restore the datbase with the dump file
            Log::debug('Loading mysql dump file to restore database');
            $dbFile = Storage::disk('backups')->get($this->basename.'db-dumps'.DIRECTORY_SEPARATOR.'mysql-tech-bench.sql');
            DB::unprepared($dbFile);

            //  Run any migrations in case the application is newer than the backup
            $this->callSilently('migrate');

            return true;
        }

        return false;
    }

    /**
     * Copy all of the files from a folder into a disk instance
     */
    protected function copyDisk($disk)
    {
        //  Get the root folder name
        $folder = config('filesystems.disks.'.$disk.'.base_folder');
        $files  = Storage::disk('backups')->allFiles($this->basename.$folder.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'app');

        foreach($files as $file)
        {
            $data    = Storage::disk('backups')->get($file);
            //  Trim the file path to the correct new path
            $rename = str_replace($this->basename.'app'.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR, '', $file);

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
                Log::debug('Deleted file '.$file.' from disk - '.$disk);
            }
        }

        //  Clear all sub directories from the disk
        $folders = Storage::disk($disk)->directories();
        foreach($folders as $folder)
        {
            Storage::disk($disk)->deleteDirectory($folder);
            Log::debug('Deleted Directory - '.$folder.' from disk - '.$disk);
        }
    }
}
