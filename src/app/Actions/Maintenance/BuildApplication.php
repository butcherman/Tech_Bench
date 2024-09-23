<?php

namespace App\Actions\Maintenance;

use App\Events\Admin\AdministrationEvent;
use App\Http\Requests\Admin\BasicSettingsRequest;
use App\Http\Requests\Admin\EmailSettingsRequest;
use App\Http\Requests\Admin\PasswordPolicyRequest;
use App\Models\User;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Facades\Hash;

class BuildApplication
{
    use AppSettingsTrait;

    protected bool $success = false;

    public function __construct(public array $appSettingsData)
    {
        $this->init();
    }

    protected function init()
    {
        $this->buildUserSettings();
        $this->buildAdminAccount();
        $this->setAdminPassword();
        $this->buildEmailSettings();
        $this->buildBasicSettings();
        $this->success = true;

        $this->clearSetting('app.first_time_setup');
        $this->cacheConfig();
        event(new AdministrationEvent('Setup Complete'));
    }

    /**
     * Build the Basic App Settings
     */
    protected function buildBasicSettings()
    {
        event(new AdministrationEvent('Saving App Settings'));

        $process = new BasicSettingsRequest($this->appSettingsData['basic-settings']);
        $process->processSettings();

    }

    /**
     * Build the Email Settings
     */
    protected function buildEmailSettings()
    {
        event(new AdministrationEvent('Saving Email Settings'));

        $process = new EmailSettingsRequest($this->appSettingsData['email-settings']);
        $process->processSettings();

    }

    /**
     * Build the User Password Policy Settings
     */
    protected function buildUserSettings()
    {
        event(new AdministrationEvent('Saving Password Policy'));

        $process = new PasswordPolicyRequest($this->appSettingsData['user-settings']);
        $process->processPasswordSettings();

    }

    /**
     * Update the Administrator Account
     */
    protected function buildAdminAccount()
    {
        event(new AdministrationEvent('Saving Admin Account'));

        User::find(1)->update($this->appSettingsData['admin']);

    }

    /**
     * Set the Administrator Password
     */
    protected function setAdminPassword()
    {
        event(new AdministrationEvent('Saving Administrator Password'));

        $pass = $this->appSettingsData['administrator-password']['password'];
        $user = User::find(1);
        $user->forceFill([
            'password' => Hash::make($pass),
            'password_expires' => $user->getNewExpireTime(),
        ])->save();

    }
}
