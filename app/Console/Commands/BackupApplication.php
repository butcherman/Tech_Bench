<?php

namespace App\Console\Commands;

use Zip;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupApplication extends Command
{
    //  Command Name
    protected $signature = 'backup:now {type=manual}';

    //  Command description
    protected $description = 'Create a full backup of the Tech Bench Application';

    //  Constructor
    public function __construct()
    {
        parent::__construct();
    }

    //  Create backup of system
    public function handle()
    {
        $backupType = $this->argument('type');
        $backupDir = config('filesystems.disks.backup.root');
        $backupTmp = config('filesystems.disks.backup.root').DIRECTORY_SEPARATOR.'tmp';
        $localDir  = config('filesystems.disks.local.root');
        $publicDir = config('filesystems.disks.public.root');
        $backupBase = $backupType.'_backup-'.Carbon::now()->toDateString().'_'.Carbon::now()->hour.Carbon::now()->minute;
                
        //  Determine if the backup file already exists
        $i = 0;
        do
        {
            if($i)
            {
                $backupName = $backupBase.'('.$i.')';
            }
            else
            {
                $backupName = $backupBase;
            }
            $i++;
        } while(Storage::disk('backup')->exists($backupName.'.zip'));
        
        //  Write a file that shows the system version    
        $version = new \PragmaRX\Version\Package\Version();
        Storage::disk('backup')->put('tmp/version.txt', $version->compact());
        
        //  Create a dump file of the MySQL database
        $process = new Process(/** @scrutinizer ignore-type */ sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $backupTmp.DIRECTORY_SEPARATOR.'db_dump.sql'
        ));
        $process->mustRun();
        
        //  Create a zip archive of the tmp folder
        $zip = Zip::create($backupDir.DIRECTORY_SEPARATOR.$backupName.'.zip');        
        $zip->add($backupTmp.DIRECTORY_SEPARATOR.'version.txt');
        $zip->add($backupTmp.DIRECTORY_SEPARATOR.'db_dump.sql');
        $zip->add(base_path('.env'));
        $zip->add($localDir);
        $zip->add($publicDir);
        $zip->add(storage_path('logs'));
        $zip->close();
        
        //  Clean up the tmp folder
        Storage::disk('backup')->deleteDirectory('tmp');
        
        Log::notice('Backup file created successfully');
        $this->line('Backup Successful');
    }
}
