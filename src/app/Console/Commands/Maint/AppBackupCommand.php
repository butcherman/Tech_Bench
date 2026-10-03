<?php

namespace App\Console\Commands\Maint;

use App\Enums\BackupType;
use App\Jobs\Maintenance\RunBackupJob;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class AppBackupCommand extends Command
{
    /**
     * The name and signature of the console command
     *
     * @var string
     */
    protected $signature = 'app:backup';

    /**
     * The console command description
     *
     * @var string
     */
    protected $description = 'Manual Tech Bench Backup';

    /**
     * Execute the command.
     */
    public function handle(): void
    {
        $this->line('Running System Backup');

        RunBackupJob::dispatchSync(BackupType::Cli);
    }
}
