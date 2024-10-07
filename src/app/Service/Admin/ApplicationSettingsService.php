<?php

namespace App\Service\Admin;

use App\Events\Config\UrlChangedEvent;
use App\Events\Feature\FeatureChangedEvent;
use App\Http\Requests\Admin\BasicSettingsRequest;
use App\Http\Requests\Admin\EmailSettingsRequest;
use App\Http\Requests\Admin\FeatureConfigRequest;
use App\Http\Requests\Admin\LogoRequest;
use App\Models\AppSettings;
use Illuminate\Http\File;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApplicationSettingsService
{
    /**
     * Update the Basic Settings for Tech Bench
     */
    public function updateBasicSettings(BasicSettingsRequest $requestData): void
    {
        $baseUrl = str_replace('https://', '', config('app.url'));

        // Changing the URL will trigger a rebuild of all JS files
        if ($baseUrl !== $requestData->url) {
            event(new UrlChangedEvent($requestData->url, $baseUrl));
            $this->saveSettings('app.url', $requestData->url);
        }

        $setArr = [
            'app.timezone' => $requestData->timezone,
            'app.company_name' => $requestData->company_name,
            'app.schedule_timezone' => $requestData->timezone,
            'filesystems.max_filesize' => $requestData->max_filesize,
            'services.azure.redirect' => 'https://'.$requestData->url.'/auth/callback',
        ];

        $this->saveSettingsArray($setArr);

        $user = $requestData->user() ? $requestData->user()->username : 'Initial Setup Wizard';
        Log::notice(
            'Application Configuration updated by '.$user,
            $requestData->toArray()
        );
    }

    /**
     * Save the new email settings
     */
    public function processEmailSettings(EmailSettingsRequest $requestData): void
    {
        $this->saveSettings('mail.from.address', $requestData->from_address);
        $this->saveSettingsArray($requestData->except('from_address'), 'mail.mailers.smtp');

        $user = $requestData->user() ? $requestData->user()->username : 'Initial Setup Wizard';
        Log::notice(
            'Email Settings Updated by '.$user,
            $requestData->except('password')
        );
    }

    /**
     * Save the new Feature Settings
     */
    public function updateFeatureSettings(FeatureConfigRequest $requestData): void
    {
        $this->saveSettings('file-link.feature_enabled', $requestData->file_links);
        $this->saveSettings('tech-tips.allow_public', $requestData->public_tips);
        $this->saveSettings('tech-tips.allow_comments', $requestData->tip_comments);

        // Forget the feature settings to re-force checking
        event(new FeatureChangedEvent);

        $user = $requestData->user() ? $requestData->user()->username : 'Initial Setup Wizard';
        Log::info('Application Features updated by '.$user);
    }

    /**
     * Save the Logo File
     */
    public function processLogo(LogoRequest $requestData): void
    {
        $path = 'images/logo';
        $storedFile = Storage::disk('public')->putFile($path, new File($requestData->file));

        $this->saveSettings('app.logo', '/storage/'.$storedFile);

        $user = $requestData->user() ? $requestData->user()->username : 'Initial Setup Wizard';
        Log::notice('New Tech Bench Logo uploaded by '.$user, [
            'file-location' => $storedFile,
        ]);
    }

    /***************************************************************************/

    /**
     * Save an individual setting into the database
     */
    public function saveSettings(string $key, mixed $value): void
    {
        // Verify that we are not trying to save over a fake password
        if ($value !== __('admin.fake-password') && $value !== null) {

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
            }
        }

        $this->cacheConfig();
    }

    /**
     * Array must be in the form of ['key' => 'value] in order to be properly updated
     */
    public function saveSettingsArray(array $settingArray, ?string $prefix = null)
    {
        foreach ($settingArray as $key => $value) {
            $newKey = is_null($prefix) ? $key : $prefix.'.'.$key;

            $this->saveSettings($newKey, $value);
        }

        $this->cacheConfig();
    }

    /**
     * Clear a setting from the database
     */
    public function clearSetting($key)
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
    protected function cacheConfig()
    {
        if (App::environment('production')) {
            Artisan::call('config:cache');
            Log::debug('Caching new config');
        }
    }
}
