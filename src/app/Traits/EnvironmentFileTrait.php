<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

trait EnvironmentFileTrait
{
    /**
     * Get the value of a current .env key
     */
    protected function getEnvKeyValue(string $key)
    {
        preg_match(
            '/^'.$key.'=(.*)/m',
            file_get_contents($this->laravel->environmentFilePath()),
            $data
        );

        return end($data);
    }

    /**
     * Get a regex pattern that will match the given key
     */
    protected function getKeyReplacementPattern(string $key)
    {
        $curValue = $this->getEnvKeyValue($key);
        $escaped = preg_quote('='.$curValue, '/');

        return '/^'.$key.$escaped.'/m';
    }

    /**
     * Replace an existing key in the .env file with a new value
     */
    protected function writeNewEnvironmentFileReplacing(string $key, string $newValue)
    {
        $replaced = preg_replace(
            $this->getKeyReplacementPattern($key),
            $key.'='.$newValue,
            $input = file_get_contents($this->laravel->environmentFilePath())
        );

        if ($replaced === $input || $replaced === null) {
            return false;
        }

        file_put_contents($this->laravel->environmentFilePath(), $replaced);

        return true;
    }

    /**
     * Write a new env key with new value
     */
    protected function writeNewEnvironmentFileWith(string $key, string $value)
    {
        $input = file_get_contents($this->laravel->environmentFilePath());

        if ($input === null) {
            return false;
        }

        $input .= "\n{$key}={$value}\n";

        file_put_contents($this->laravel->environmentFilePath(), $input);

        return true;
    }

    /**
     * If the app is in production mode, we will re-cache the config
     */
    protected function cacheConfig()
    {
        if (App::environment('Production')) {
            Artisan::call('config:cache');
        }
    }
}
