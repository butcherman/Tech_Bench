<?php

namespace App\Console\Commands\Setup;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Str;

class GenerateReverbConfigCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command
     */
    protected $signature = 'reverb:generate
                                {--force : Force the operation to run when in production}';

    /**
     * The console command description
     */
    protected $description = 'Generate new Credentials for Pusher App';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentId = config('broadcasting.connections.reverb.app_id');
        $currentKey = config('broadcasting.connections.reverb.key');
        $currentSecret = config('broadcasting.connections.reverb.secret');

        if (! $this->confirmToProceed()) {
            return;
        }

        $this->line('working so far');

        $this->writeNewEnvironmentFileWith('REVERB_APP_ID', $currentId);
        $this->writeNewEnvironmentFileWith('REVERB_APP_KEY', $currentKey);
        $this->writeNewEnvironmentFileWith('REVERB_APP_SECRET', $currentSecret);
    }

    /**
     * Write a new environment file with the given key
     */
    protected function writeNewEnvironmentFileWith(string $key, string $curValue)
    {
        $replaced = preg_replace(
            $this->keyReplacementPattern($key, $curValue),
            $key.'='.$this->generateRandomKey(),
            $input = file_get_contents($this->laravel->environmentFilePath())
        );

        if ($replaced === $input || $replaced === null) {
            $this->error('Unable to set application key. No '.$key.' variable was found in the .env file.');

            return false;
        }

        file_put_contents($this->laravel->environmentFilePath(), $replaced);

        return true;
    }

    /**
     * Generate a random key
     */
    protected function generateRandomKey()
    {
        return Str::ulid();
    }

    /**
     * Get a regex pattern that will match the REVERB
     */
    protected function keyReplacementPattern(string $prefix, string $curValue)
    {
        $escaped = preg_quote('='.$curValue);

        return "/^{$prefix}{$escaped}/m";
    }
}
