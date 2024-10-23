<?php

namespace App\Service\Admin;

use App\Events\Config\UrlChangedEvent;
use App\Events\Feature\FeatureChangedEvent;
use App\Http\Requests\Admin\BasicSettingsRequest;
use App\Http\Requests\Admin\EmailSettingsRequest;
use App\Http\Requests\Admin\FeatureConfigRequest;
use App\Http\Requests\Admin\LogoRequest;
use App\Http\Requests\Admin\PasswordPolicyRequest;
use App\Traits\AppSettingsTrait;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
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

        $user = $requestData->user()
            ? $requestData->user()->username
            : 'Initial Setup Wizard';

        Log::notice(
            'Application Configuration updated by '.$user,
            $requestData->toArray()
        );
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
            'password' => config('mail.mailers.smtp.password') ? __('admin.fake_password') : '',
            'require_auth' => (bool) config('mail.mailers.smtp.require_auth'),
        ];
    }

    /**
     * Save the new email settings
     */
    public function processEmailSettings(EmailSettingsRequest $requestData): void
    {
        $this->saveSettings('mail.from.address', $requestData->from_address);
        $this->saveSettingsArray(
            $requestData->except('from_address'),
            'mail.mailers.smtp'
        );

        $user = $requestData->user()
            ? $requestData->user()->username
            : 'Initial Setup Wizard';

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

        $user = $requestData->user()
            ? $requestData->user()->username
            : 'Initial Setup Wizard';

        Log::info('Application Features updated by '.$user);
    }

    /**
     * Save the Logo File
     */
    public function processLogo(LogoRequest $requestData): void
    {
        $path = 'images/logo';
        $storedFile = Storage::disk('public')
            ->putFile($path, new File($requestData->file));

        $this->saveSettings('app.logo', '/storage/'.$storedFile);

        $user = $requestData->user()
            ? $requestData->user()->username
            : 'Initial Setup Wizard';

        Log::notice('New Tech Bench Logo uploaded by '.$user, [
            'file-location' => $storedFile,
        ]);
    }

    /**
     * Get the password Policy settings
     */
    public function getPasswordSettings(): array
    {
        return [
            'expire' => config('auth.passwords.settings.expire'),
            'min_length' => config('auth.passwords.settings.min_length'),
            'contains_uppercase' => (bool) config('auth.passwords.settings.contains_uppercase'),
            'contains_lowercase' => (bool) config('auth.passwords.settings.contains_lowercase'),
            'contains_number' => (bool) config('auth.passwords.settings.contains_number'),
            'contains_special' => (bool) config('auth.passwords.settings.contains_special'),
            'disable_compromised' => (bool) config('auth.passwords.settings.disable_compromised'),
        ];
    }

    /**
     * Update the User Password Policy
     */
    public function processPasswordSettings(PasswordPolicyRequest $requestData): void
    {
        $this->saveSettingsArray($requestData->toArray(), 'auth.passwords.settings');

        $user = $requestData->user()
            ? $requestData->user()->username
            : 'Initial Setup Wizard';

        Log::notice($user.' has updated the User Password Policy',
            $requestData->toArray());
    }
}
