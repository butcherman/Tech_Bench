<?php

namespace App\Actions\Maintenance;

use App\Services\_Base\ApplicationEnvironment;
use Illuminate\Support\Str;

class GenerateReverbCredentials extends ApplicationEnvironment
{
    /**
     * List of keys needed for Reverb Application.
     *
     * @var array<int, string>
     */
    protected $keyList = [
        'REVERB_APP_ID',
        'REVERB_APP_KEY',
        'REVERB_APP_SECRET',
    ];

    /**
     * Generate new credentials for Reverb.  If the entries do not exist in
     * the .env file, create them.
     */
    public function __invoke(): void
    {
        foreach ($this->keyList as $key) {
            $success = $this->writeNewEnvironmentFileReplacing(
                $key,
                $this->generateRandomKey()
            );

            // If replace key did not work, write new key
            if (! $success) {
                $this->writeNewEnvironmentFileWith(
                    $key,
                    $this->generateRandomKey()
                );
            }
        }

        $this->rebuildCache();
        $this->rebuildAppFiles();
    }

    /**
     * Generate a random value string
     */
    protected function generateRandomKey()
    {
        return Str::ulid();
    }
}
