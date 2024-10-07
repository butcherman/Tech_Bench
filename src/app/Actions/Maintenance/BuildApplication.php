<?php

namespace App\Actions\Maintenance;

use App\Events\Admin\AdministrationEvent;
use App\Http\Requests\Admin\BasicSettingsRequest;
use App\Http\Requests\Admin\EmailSettingsRequest;
use App\Http\Requests\Admin\PasswordPolicyRequest;
use App\Models\User;
use App\Service\Admin\ApplicationSettingsService;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class BuildApplication
{
    use AppSettingsTrait;

    protected bool $success = false;

    protected $svc;

    public function __construct(public array $appSettingsData)
    {
        $this->svc = new ApplicationSettingsService;
    }

    /**
     * Save all of the initial setup wizard changes
     */
    public function __invoke(): void
    {
        $this->buildUserSettings();
        $this->buildAdminAccount();
        $this->setAdminPassword();
        $this->buildEmailSettings();
        $this->buildBasicSettings();
        $this->success = true;

        $this->clearSetting('app.first_time_setup');
        event(new AdministrationEvent('Setup Complete'));

        // @codeCoverageIgnoreStart
        if (App::environment('production')) {
            Artisan::call('optimize');
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Build the Basic App Settings
     */
    protected function buildBasicSettings(): void
    {
        event(new AdministrationEvent('Saving App Settings'));

        $process = new BasicSettingsRequest($this->appSettingsData['basic-settings']);
        $this->svc->updateBasicSettings($process);
    }

    /**
     * Build the Email Settings
     */
    protected function buildEmailSettings(): void
    {
        event(new AdministrationEvent('Saving Email Settings'));

        $process = new EmailSettingsRequest($this->appSettingsData['email-settings']);
        $this->svc->processEmailSettings($process);
    }

    /**
     * Build the User Password Policy Settings
     */
    protected function buildUserSettings(): void
    {
        event(new AdministrationEvent('Saving Password Policy'));

        $process = new PasswordPolicyRequest($this->appSettingsData['user-settings']);
        $process->processPasswordSettings();
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
}
