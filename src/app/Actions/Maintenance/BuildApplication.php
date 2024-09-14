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
        event(new AdministrationEvent('Setup Complete'));
    }

    /**
     * Build the Basic App Settings
     */
    protected function buildBasicSettings()
    {
        $process = new BasicSettingsRequest($this->appSettingsData['basic-settings']);
        $process->processSettings();

        event(new AdministrationEvent('Settings Saved'));
    }

    /**
     * Build the Email Settings
     */
    protected function buildEmailSettings()
    {
        $process = new EmailSettingsRequest($this->appSettingsData['email-settings']);
        $process->processSettings();

        event(new AdministrationEvent('Email Settings Updated'));
    }

    /**
     * Build the User Password Policy Settings
     */
    protected function buildUserSettings()
    {
        $process = new PasswordPolicyRequest($this->appSettingsData['user-settings']);
        $process->processPasswordSettings();

        event(new AdministrationEvent('User Settings Updated'));
    }

    /**
     * Update the Administrator Account
     */
    protected function buildAdminAccount()
    {
        User::find(1)->update($this->appSettingsData['admin']);

        event(new AdministrationEvent('Admin Account Updated'));
    }

    /**
     * Set the Administrator Password
     */
    protected function setAdminPassword()
    {
        $pass = $this->appSettingsData['administrator-password']['password'];
        $user = User::find(1);
        $user->forceFill([
            'password' => Hash::make($pass),
            'password_expires' => $user->getNewExpireTime(),
        ])->save();

        event(new AdministrationEvent('Admin Password Updated'));
    }
}
