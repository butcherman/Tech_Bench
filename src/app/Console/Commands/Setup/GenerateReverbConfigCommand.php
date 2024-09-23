<?php

namespace App\Console\Commands\Setup;

use App\Traits\EnvironmentFileTrait;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Str;

class GenerateReverbConfigCommand extends Command
{
    use ConfirmableTrait;
    use EnvironmentFileTrait;

    /**
     * The name and signature of the console command
     */
    protected $signature = 'reverb:generate
                                {--force : Force the operation to run when in production}';

    /**
     * The console command description
     */
    protected $description = 'Generate new Credentials for Reverb App';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return;
        }

        $success = true;
        $keyList = [
            'REVERB_APP_ID',
            'REVERB_APP_KEY',
            'REVERB_APP_SECRET',
        ];

        foreach ($keyList as $key) {
            $passed = $this->writeNewEnvironmentFileReplacing($key, $this->generateRandomKey());
            if (! $passed) {
                $this->error('Unable to set .env variable for '.$key.'.  This key does not exist');
                $success = false;
            }
        }

        $this->cacheConfig();
        $this->newLine();
        if ($success) {
            $this->components->info('Reverb credentials created successfully.');
        } else {
            $this->components->error('One or more errors occurred.  Please check errors above.');
        }
    }

    /**
     * Generate a random value string
     */
    protected function generateRandomKey()
    {
        return Str::ulid();
    }
}
