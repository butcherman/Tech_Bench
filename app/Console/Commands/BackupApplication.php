<?php

namespace App\Console\Commands;

use Zip;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupApplication extends Command
{
    //  Command Name
    protected $signature = 'backup:now';

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
        $backupDir = config('filesystems.disks.backup.root');
        $backupTmp = config('filesystems.disks.backup.root').DIRECTORY_SEPARATOR.'tmp';
        $localDir  = config('filesystems.disks.local.root');
        $publicDir = config('filesystems.disks.local.root');
        
        
        //  Write a file that shows the system version    
        $version = new \PragmaRX\Version\Package\Version();
        Storage::disk('backup')->put('tmp/version.txt', $version->compact());
        
        //  Create a dump file of the MySQL database
        $process = new Process(sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $backupTmp.DIRECTORY_SEPARATOR.'db_dump.sql'
        ));
        $process->mustRun();
        
        //  Create a zip archive of the tmp folder
        $zip = Zip::create($backupDir.DIRECTORY_SEPARATOR.'backup-'.Carbon::now()->toDateString().'.zip');        
        $zip->add($backupTmp.DIRECTORY_SEPARATOR.'version.txt');
        $qip->add($backupTmp.DIRECTORY_SEPARATOR.'db_dump.sql');
        $zip->add(base_path('.env'));
        $zip->add(storage_path('app/files'));
        $zip->add(storage_path('app/public'));
        $zip->add(storage_path('logs'));
        $zip->close();
        
        //  Clean up the tmp folder
        Storage::disk('backup')->deleteDirectory('tmp');
        
        
        echo $version->compact();
    }
}
