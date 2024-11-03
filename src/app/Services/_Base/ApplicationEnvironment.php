<?php

namespace App\Services\_Base;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

abstract class ApplicationEnvironment
{
    /**
     * Location of the .env file
     *
     * @var string
     */
    protected $envPath;

    public function __construct()
    {
        $this->envPath = App::environmentFilePath();
    }

    /**
     * Get the value of a current .env key
     */
    protected function getEnvKeyValue(string $key): string
    {
        preg_match(
            '/^'.$key.'=(.*)/m',
            file_get_contents($this->envPath),
            $data
        );

        return end($data);
    }

    /**
     * Write a new env key with new value
     */
    protected function writeNewEnvironmentFileWith(string $key, string $value): bool
    {
        $input = file_get_contents($this->envPath);

        if ($input === null) {
            return false;
        }

        $input .= "\n{$key}={$value}\n";

        file_put_contents($this->envPath, $input);

        return true;
    }

    /**
     * Replace an existing key in the .env file with a new value
     */
    protected function writeNewEnvironmentFileReplacing(
        string $key,
        string $newValue
    ): bool {
        $replaced = preg_replace(
            $this->getKeyReplacementPattern($key),
            $key.'='.$newValue,
            $input = file_get_contents($this->envPath)
        );

        if ($replaced === $input || $replaced === null) {
            return false;
        }

        file_put_contents($this->envPath, $replaced);

        return true;
    }

    /**
     * Get a regex pattern that will match the given key
     */
    private function getKeyReplacementPattern(string $key): string
    {
        $curValue = $this->getEnvKeyValue($key);
        $escaped = preg_quote('='.$curValue, '/');

        return '/^'.$key.$escaped.'/m';
    }

    /**
     * Clear and rebuild the existing configuration cache
     */
    protected function rebuildCache(): void
    {
        if (App::environment('production')) {
            Log::notice('Rebuilding Configuration Cache');

            // Clear the existing cache
            Artisan::call('optimize:clear');

            // Rebuild new Cache
            Artisan::call('breadcrumbs:cache');
            Artisan::call('optimize');
        }
    }

    /**
     * Use NPM to rebuild application JS files
     */
    protected function rebuildAppFiles(): void
    {
        if (App::environment('production')) {
            Log::notice('Running NPM Build to Application Files');

            Process::run('npm run build');
        }
    }
}
