<?php

namespace App\Actions\Maintenance;

use App\Exceptions\Maintenance\BackupFailedException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\ConsoleOutput;

class RunDatabaseBackup
{
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initialize the backup process
     */
    protected function init()
    {
        if ($this->checkDiskSpace()) {
            throw new BackupFailedException('Not enough disk space');
        }

        Artisan::call('backup:run', [], new ConsoleOutput);
    }

    /**
     * Check the disk space to make sure there is enough to run the backup
     */
    protected function checkDiskSpace()
    {
        $freeSpace = disk_free_space('/app');
        $totalSpace = disk_total_space('/app');
        $usedSpace = $totalSpace - $freeSpace;
        $percentage = round(($usedSpace / $totalSpace * 100), 2);

        Log::debug('Disk Free Space - '.$freeSpace);
        Log::debug('Total Disk Space - '.$totalSpace);
        Log::debug('Disk Used Space - '.$usedSpace);
        Log::debug('Disk Percentage Used - '.$percentage.'%');

        return $percentage > 70;
    }
}
