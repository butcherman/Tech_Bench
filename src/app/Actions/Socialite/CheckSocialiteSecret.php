<?php

namespace App\Actions\Socialite;

use App\Mail\Admin\AzureCertificateExpiredMail;
use App\Mail\Admin\AzureCertificateExpiresSoonMail;
use App\Models\User;
use App\Services\Admin\UserGlobalSettingsService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckSocialiteSecret extends UserGlobalSettingsService
{
    /**
     * # of days before the certificate expires that a notification should be sent
     *
     * @var array
     */
    protected $notificationDays = [15, 30, 60, 90];

    /**
     * Check if the Azure Client Secret is set to expire soon.  If so, email
     * the users with the Installer Role to notify them of the upcoming
     * expiration date.
     */
    public function __invoke(): void
    {
        Log::info('Checking Azure Certificate expiration date');

        $daysToExpire = $this->getOathCertExpiresDays();
        $notificationGroup = User::where('role_id', 1)->get();

        // If the cert has already expired, send expired notice
        if ($daysToExpire <= 0) {
            Mail::to($notificationGroup)->send(new AzureCertificateExpiredMail);
            Log::alert('Azure Certificate has expired.  SSO will no long work');

            return;
        }

        // If the cert is going to expire in <notification days> send about to expire notice
        if (in_array($daysToExpire, $this->notificationDays)) {
            Mail::to($notificationGroup)
                ->send(new AzureCertificateExpiresSoonMail($daysToExpire));
            Log::notice('Azure Certificate will expire in '.$daysToExpire.' days.');

            return;
        }

        Log::info(
            'Azure Certificate is valid, no action needed'
        );
    }
}
