<?php

namespace App\Actions;

/**
 * Build arrays that have the different settings for User Administration
 */
class BuildAdminUserSettings
{
    /**
     * Two Factor Authentication Settings
     */
    public function buildTwoFaSettings()
    {
        return [
            'required' => (bool) config('auth.twoFa.required'),
            'allow_save_device' => (bool) config('auth.twoFa.allow_save_device'),
            'allow_via_email' => (bool) config('auth.twoFa.allow_via_email'),
            'allow_via_sms' => (bool) config('auth.twoFa.allow_via_sms'),
        ];
    }

    /**
     * Open Auth/Office 365 login settings
     */
    public function buildOathSettings()
    {
        return [
            'allow_login' => (bool) config('services.azure.allow_login'),
            'allow_register' => (bool) config('services.azure.allow_register'),
            'tenant' => config('services.azure.tenant'),
            'client_id' => config('services.azure.client_id'),
            'client_secret' => config('services.azure.client_secret') ? __('admin.fake-password') : '',
            'secret_expires' => config('services.azure.secret_expires'),
            'redirectUri' => config('app.url').'/auth/callback',
        ];
    }

    /**
     * 3rd Party Twilio settings for SMS
     */
    public function buildTwilioSettings()
    {
        return [
            'sid' => config('services.twilio.sid'),
            'token' => config('services.twilio.token'),
            'from' => config('services.twilio.from'),
        ];
    }
}
