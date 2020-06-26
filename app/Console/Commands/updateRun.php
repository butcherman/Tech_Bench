<?php

namespace App\Console\Commands;

use Zip;
use Exception;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class updateRun extends Command
{
    protected $signature   = 'tb-update:run';
    protected $description = 'Update the Tech Bench to a newer version';

    public function handle()
    {
        $this->line('');
        //  Select which update file to use
        $updateFile = $this->checkForUpdate();
        if($updateFile)
        {
            //  Open up the file and verify it is at least the same version as the current setup
            $valid = $this->openUpdate($updateFile);
            if($valid >= 0)
            {
                $this->call('down');
                if($this->copyFiles($updateFile))
                {
                    $this->info('Update Completed');
                }
                else
                {
                    $this->error('Unable to update.  Check the logs for details');
                }

            }
            else
            {
                $this->error('The selected update is not a newer version');
                $this->error('Cannot downgrade Tech Bench in this manner');
            }
        }
    }

    //  Check to see if any backups are in the backup folder
    protected function checkForUpdate()
    {
        $updateList = [];
        $updates    = Storage::disk('staging')->files('updates');

        //  See if the temporary directory already exists, or if it needs to be deleted
        Storage::disk('staging')->deleteDirectory('updates'.DIRECTORY_SEPARATOR.'tmp');

        //  Cycle through each file in the update directory to see if they are update files
        foreach($updates as $update)
        {
            $baseName = explode('/', $update)[1];

            //  Verify the file is in the .zip format
            $fileParts = pathinfo($baseName);
            if($fileParts['extension'] == 'zip')
            {
                //  Verify this is actually an update file
                $zip = Zip::open(config('filesystems.disks.staging.root').
                    DIRECTORY_SEPARATOR.'updates'.DIRECTORY_SEPARATOR.$baseName);
                $files = $zip->listFiles();
                if(in_array($fileParts['filename'].'/config/version.yml', $files))
                {
                    $updateList[] = $baseName;
                }
            }
        }

        if(empty($updateList))
        {
            $this->error('No updates have been loaded to the system');
            $this->error('Please upload update package to the Storage/Staging/Updates folder');
            return false;
        }

        //  Determine if there is more than one update that can be applied
        if(count($updateList) > 1)
        {
            $this->line('');

            $anticipate = [];
            foreach($updateList as $key => $up)
            {
                $opt = $key;
                $anticipate[$opt] = $up;
            }
            $updateFile = $this->choice('Please select which update you would like to load', $anticipate);
        }
        else
        {
            $updateFile = $updateList[0];
        }

        return $updateFile;
    }

    //  Unzip the update and verify it is a newer or the same version
    protected function openUpdate($file)
    {
        $fileParts = pathinfo($file);
        $folder = $fileParts['filename'];

        $zip = Zip::open(config('filesystems.disks.staging.root').
            DIRECTORY_SEPARATOR.'updates'.DIRECTORY_SEPARATOR.$file);

        $zip->extract(config('filesystems.disks.staging.root').
            DIRECTORY_SEPARATOR.'updates'.DIRECTORY_SEPARATOR.'tmp');
        $zip->close();

        $verFile = fopen(config('filesystems.disks.staging.root').
            DIRECTORY_SEPARATOR.'updates'.DIRECTORY_SEPARATOR.'tmp'.
            DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.'config'.
            DIRECTORY_SEPARATOR.'version.yml', 'r');

        $verData = [];
        $i = 0;
        while(!feof(
        /** @scrutinizer ignore-type */
        $verFile))
        {
            $line = fgets(
            /** @scrutinizer ignore-type */
            $verFile);
            $data = explode(':', $line);

            if(($data[0] === '  major' || $data[0] === '  minor' || $data[0] === '  patch') && $i < 3)
            {
                $verData[trim($data[0])] = trim($data[1]);
                $i++;
            }
        }
        $newVersion = $verData['major'].'.'.$verData['minor'].'.'.$verData['patch'];

        $verObj = new \PragmaRX\Version\Package\Version();
        $curVersion = $verObj->major().'.'.$verObj->minor().'.'.$verObj->patch();

        $valid = version_compare($newVersion, $curVersion);

        return $valid;
    }

    //  copy the update files to the proper folders
    protected function copyFiles($updateFile)
    {
        //  Verify that the root directory is writable
        try
        {
            File::put(base_path().DIRECTORY_SEPARATOR.'.ignore', '');
        }
        catch(Exception $e)
        {
            Log::emergency('Unable to Update, Web Root Directory is not writable');
            return false;
        }

        $fileParts = pathinfo($updateFile);
        $folder = $fileParts['filename'];

        $updateFile = config('filesystems.disks.staging.root').
            DIRECTORY_SEPARATOR.'updates'.DIRECTORY_SEPARATOR.'tmp'.
            DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR;
        $backupFile = config('filesystems.disks.staging.root').
            DIRECTORY_SEPARATOR.'updates'.DIRECTORY_SEPARATOR.'tmp'.
            DIRECTORY_SEPARATOR.'backup';
        File::makeDirectory($backupFile);

        $folderArr = ['app', 'bootstrap', 'config', 'database', 'public', 'routes'];
        $fileArr   = ['artisan', 'composer.json', 'package-lock.json', 'server.php', 'webpack.mix.js'];

        //  Backup existing folders wile copying new ones
        foreach($folderArr as $newFolder)
        {
            File::copyDirectory(base_path().DIRECTORY_SEPARATOR.$newFolder, $backupFile.DIRECTORY_SEPARATOR.$newFolder);
            File::deleteDirectory(base_path().DIRECTORY_SEPARATOR.$newFolder);
            File::move($updateFile.$newFolder, base_path().DIRECTORY_SEPARATOR.$newFolder);
        }

        //  Backup existing files in root directory, while copying new ones
        foreach($fileArr as $file)
        {
            File::copy(base_path().DIRECTORY_SEPARATOR.$file, $backupFile.DIRECTORY_SEPARATOR.$file);
            File::delete(base_path().DIRECTORY_SEPARATOR.$file);
            File::move($updateFile.$file, base_path().DIRECTORY_SEPARATOR.$file);
        }

        //  Run Composer Updates
        exec('cd '.base_path().' && composer install --no-dev --no-interaction --optimize-autoloader --no-ansi');
        $this->callSilent('ziggy:generate');
        //  Run NPM Updates
        exec('cd '.base_path().' && npm install --only=production');
        exec('cd '.base_path().' && npm run production');

        //  Update the database
        $this->call('migrate', ['--force' => 'default']);

        //  Update the cache
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');

        //  Put the app back in working order
        $this->call('up');

        return true;
    }
}
