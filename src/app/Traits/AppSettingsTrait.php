<?php

namespace App\Traits;

use App\Models\AppSettings;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

/*
|-------------------------------------------------------------------------------
| App Settings trait will set and clear configuration settings
|-------------------------------------------------------------------------------
*/

trait AppSettingsTrait
{
    /**
     * Save an individual setting into the database so that it can be modified
     * from the hard coded setting
     */
    protected function saveSettings(string $key, mixed $value): void
    {
        // Verify that we are not trying to save over a fake password
        if ($value !== __('admin.fake-password') && $value !== null) {
            Log::debug('Saving Permission Value for ' . $key);

            // Verify that the value has actually changed
            if (config($key) != $value) {
                AppSettings::firstOrCreate(
                    ['key' => $key],
                    ['value' => json_encode($value)]
                )->update(['value' => json_encode($value)]);

                Log::debug('App Settings Updated', [
                    'key' => $key,
                    'value' => $value,
                ]);
            } else {
                Log::debug('Value not changed, skipping');
            }
        }

        $this->cacheConfig();
    }

    /**
     * Array must be in the form of ['key' => 'value] in order to be properly
     * updated
     */
    protected function saveSettingsArray(array $settingArray, ?string $prefix = null): void
    {
        foreach ($settingArray as $key => $value) {
            $newKey = is_null($prefix) ? $key : $prefix . '.' . $key;

            $this->saveSettings($newKey, $value);
        }

        $this->cacheConfig();
    }

    /**
     * Clear a setting from the database
     */
    protected function clearSetting(string $key): void
    {
        $data = AppSettings::where('key', $key)->first();
        if ($data) {
            $data->delete();
        }

        $this->cacheConfig();
    }

    /**
     * Cache the current config so it is not rebuilt on every request
     *
     * @codeCoverageIgnore
     */
    protected function cacheConfig(): void
    {
        Artisan::call('cache:clear');

        if (App::environment('production')) {
            Artisan::call('config:cache');
            Log::debug('Caching new config');
        }
    }
}
