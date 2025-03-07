<?php

namespace App\Actions\Init;

use App\Events\Admin\AdministrationEvent;
use App\Models\User;
use App\Services\Admin\ApplicationSettingsService;
use App\Services\Admin\UserGlobalSettingsService;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class BuildApplication
{
    use AppSettingsTrait;

    /**
     * @var array
     */
    protected $appSettingsData;

    /**
     * Save all of the initial configuration wizard changes.
     */
    public function __invoke(array $appSettingsData): void
    {
        $this->appSettingsData = $appSettingsData;

        $this->buildUserSettings();
        $this->buildAdminAccount();
        $this->setAdminPassword();
        $this->buildEmailSettings();
        $this->buildBasicSettings();

        $this->clearSetting('app.first_time_setup');

        event(new AdministrationEvent('Setup Complete'));

        // @codeCoverageIgnoreStart
        if (App::environment('production')) {
            Artisan::call('optimize');
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Build the User Password Policy Settings
     */
    protected function buildUserSettings(): void
    {
        event(new AdministrationEvent('Saving Password Policy'));

        $svc = new UserGlobalSettingsService;
        $process = collect($this->appSettingsData['user-settings']);

        $svc->savePasswordPolicy($process);
    }

    /**
     * Update the Administrator Account
     */
    protected function buildAdminAccount(): void
    {
        event(new AdministrationEvent('Saving Admin Account'));

        User::find(1)->update($this->appSettingsData['admin']);
    }

    /**
     * Set the Administrator Password
     */
    protected function setAdminPassword(): void
    {
        event(new AdministrationEvent('Saving Administrator Password'));

        $pass = $this->appSettingsData['administrator-password']['password'];
        $user = User::find(1);
        $user->forceFill([
            'password' => Hash::make($pass),
            'password_expires' => $user->getNewExpireTime(),
        ])->save();
    }

    /**
     * Build the Email Settings
     */
    protected function buildEmailSettings(): void
    {
        event(new AdministrationEvent('Saving Email Settings'));

        $svc = new ApplicationSettingsService;
        $process = collect($this->appSettingsData['email-settings']);
        $svc->updateEmailSettings($process);
    }

    /**
     * Build the Basic App Settings
     */
    protected function buildBasicSettings(): void
    {
        event(new AdministrationEvent('Saving App Settings'));

        $svc = new ApplicationSettingsService;
        $process = collect($this->appSettingsData['basic-settings']);

        $svc->updateBasicSettings($process);
    }
}
