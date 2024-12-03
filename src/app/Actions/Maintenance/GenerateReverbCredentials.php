<?php

namespace App\Actions\Maintenance;

use App\Services\_Base\ApplicationEnvironment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateReverbCredentials extends ApplicationEnvironment
{
    /**
     * Environment Variables needed for Reverb.
     *
     * @var array<int, string>
     */
    protected $keyList = [
        'REVERB_APP_ID',
        'REVERB_APP_KEY',
        'REVERB_APP_SECRET',
    ];

    /*
    |---------------------------------------------------------------------------
    | Clear and re-generate Reverb Credentials for Laravel Echo.  If the
    | credentials do not exist, create them.
    |---------------------------------------------------------------------------
    */
    public function __invoke(): void
    {
        Log::debug('Checking for Reverb Credentials');

        foreach ($this->keyList as $key) {
            // Attempt to replace the existing key.
            $passed = $this->writeNewEnvironmentFileReplacing(
                $key,
                $this->generateRandomKey()
            );
            // If the key does not exist, create it.
            if (! $passed) {
                $this->writeNewEnvironmentFileWith(
                    $key,
                    $this->generateRandomKey()
                );
            }
        }

        $this->rebuildCache();
    }

    /**
     * Generate a random value string
     */
    protected function generateRandomKey(): string
    {
        return Str::ulid();
    }
}
