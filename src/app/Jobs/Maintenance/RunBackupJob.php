<?php

namespace App\Jobs\Maintenance;

use App\Actions\Maintenance\RunBackup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class RunBackupJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
        $this->onQueue('backups');
    }

    public function middleware(): array
    {
        return [
            new WithoutOverlapping('backup_process'),
        ];
    }

    public function handle(RunBackup $backup): void
    {
        $backup->handle();
    }
}
