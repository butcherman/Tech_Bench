<?php

namespace App\Console\Commands;

use Zip;
use Illuminate\Console\Command;
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

            if($valid)
            {
                $this->call('down');
                $this->copyFiles($updateFile);

                $this->info('Update Completed');
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
            foreach($updateList as $key => $up) {
                $opt = $key + 1;
                $anticipate[$opt] = $up;
                $this->line('['.$opt.'] '.$up);
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

        $curVersion = new \PragmaRX\Version\Package\Version();

        $valid = false;
        if($verData['major'] > $curVersion->major())
        {
            $valid = true;
        }
        else if($verData['minor'] > $curVersion->minor())
        {
            $valid = true;
        }
        else if($verData['patch'] >= $curVersion->patch())
        {
            $valid = true;
        }

        return $valid;
    }

    //  copy the update files to the proper folders
    protected function copyFiles($updateFile)
    {
        $fileParts = pathinfo($updateFile);
        $folder = $fileParts['filename'];

        $updateFile = config('filesystems.disks.staging.root').
            DIRECTORY_SEPARATOR.'updates'.DIRECTORY_SEPARATOR.'tmp'.
            DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR;

        // Copy files
        File::copyDirectory($updateFile.'app',       base_path().DIRECTORY_SEPARATOR.'app');
        File::copyDirectory($updateFile.'bootstrap', base_path().DIRECTORY_SEPARATOR.'bootstrap');
        File::copyDirectory($updateFile.'config',    base_path().DIRECTORY_SEPARATOR.'config');
        File::copyDirectory($updateFile.'database',  base_path().DIRECTORY_SEPARATOR.'database');
        File::copyDirectory($updateFile.'public',    base_path().DIRECTORY_SEPARATOR.'public');
        File::copyDirectory($updateFile.'resources', base_path().DIRECTORY_SEPARATOR.'resources');
        File::copyDirectory($updateFile.'routes',    base_path().DIRECTORY_SEPARATOR.'routes');
        File::copy($updateFile.'composer.json',      base_path().DIRECTORY_SEPARATOR.'composer.json');
        File::copy($updateFile.'composer.lock',      base_path().DIRECTORY_SEPARATOR.'composer.lock');
        File::copy($updateFile.'package.json',       base_path().DIRECTORY_SEPARATOR.'package.json');
        File::copy($updateFile.'package-lock.json',  base_path().DIRECTORY_SEPARATOR.'package-lock.json');

        //  Run Composer Updates
        exec('cd '.base_path().' && composer install --no-dev --no-interaction --optimize-autoloader --no-ansi > /dev/null 2>&1');
        $this->call('ziggy:generate');
        //  Run NPM Updates
        exec('cd '.base_path().' && npm install --only=production > /dev/null 2>&1');
        exec('cd '.base_path().' && npm run production > /dev/null 2>&1');

        //  Update the database
        $this->call('migrate', ['--force' => 'default']);

        //  Update the cache
        $this->call('config:cache');
        $this->call('route:cache');

        //  Put the app back in working order
        $this->call('up');

        return true;
    }
}
