<?php

namespace App\Services\Admin;

use App\Events\Config\UrlChangedEvent;
use App\Events\Feature\FeatureChangedEvent;
use App\Facades\CacheData;
use App\Traits\AppSettingsTrait;
use Illuminate\Http\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ApplicationSettingsService
{
    use AppSettingsTrait;

    /**
     * Get an array of basic settings from config/db
     */
    public function getBasicSettings(): array
    {
        return [
            'url' => preg_replace('(^https?://)', '', config('app.url')),
            'company_name' => config('app.company_name'),
            'timezone' => config('app.timezone'),
            'max_filesize' => (int) config('filesystems.max_filesize'),
        ];
    }

    /**
     * Update the Basic Settings for Tech Bench
     */
    public function updateBasicSettings(Collection $requestData): void
    {
        $baseUrl = str_replace('https://', '', config('app.url'));

        // Changing the URL will trigger a rebuild of all JS files
        if ($baseUrl !== $requestData->get('url')) {
            event(new UrlChangedEvent($requestData->get('url'), $baseUrl));
            $this->saveSettings('app.url', $requestData->get('url'));
        }

        $setArr = [
            'app.timezone' => $requestData->get('timezone'),
            'app.company_name' => $requestData->get('company_name'),
            'app.schedule_timezone' => $requestData->get('timezone'),
            'filesystems.max_filesize' => $requestData->get('max_filesize'),
            'services.azure.redirect' => 'https://' . $requestData->get('url') . '/auth/callback',
        ];

        $this->saveSettingsArray($setArr);
    }

    /**
     * Get an array of the Email Settings
     */
    public function getEmailSettings(): array
    {
        return [
            'from_address' => config('mail.from.address'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => strtoupper(config('mail.mailers.smtp.encryption')),
            'username' => config('mail.mailers.smtp.username'),
            'password' => config('mail.mailers.smtp.password')
                ? __('admin.fake_password')
                : '',
            'require_auth' => (bool) config('mail.mailers.smtp.require_auth'),
        ];
    }

    /**
     * Save the new email settings
     */
    public function updateEmailSettings(Collection $requestData): void
    {
        $this->saveSettings('mail.from.address', $requestData->get('from_address'));

        $this->saveSettingsArray(
            $requestData->except('from_address')->toArray(),
            'mail.mailers.smtp'
        );
    }

    /**
     * Save the new Feature Settings
     */
    public function updateFeatureSettings(Collection $requestData): void
    {
        $this->saveSettings(
            'file-link.feature_enabled',
            $requestData->get('file_links')
        );
        $this->saveSettings(
            'tech-tips.allow_public',
            $requestData->get('public_tips')
        );
        $this->saveSettings(
            'tech-tips.allow_comments',
            $requestData->get('tip_comments')
        );

        // Forget the feature settings to re-force checking
        // TODO - Add Event.
        // event(new FeatureChangedEvent);
    }

    /**
     * Save the Logo File
     */
    public function updateLogo(Collection $requestData): string
    {
        $path = 'images/logo';
        $storedFile = Storage::disk('public')
            ->putFile($path, new File($requestData->get('file')));

        $this->saveSettings('app.logo', '/storage/' . $storedFile);

        CacheData::clearCache('appData');

        return $storedFile;
    }

    /**
     * Delete the Logo File
     */
    public function destroyLogo(): void
    {
        $this->clearSetting('app.logo');

        CacheData::clearCache('appData');
    }

    /**
     * Save Backup Settings
     */
    public function processBackupSettings(Collection $requestData): void
    {
        $this->saveSettingsArray($requestData->only(['nightly_backup', 'nightly_cleanup'])->toArray(), 'backup');
        $this->saveSettings('backup.backup.password', $requestData->get('password'));
        $this->saveSettings('backup.backup.encryption', $requestData->get('encryption') ? 'default' : false);
        $this->saveSettings('backup.notifications.mail.to', $requestData->get('mail_to'));
    }
}
