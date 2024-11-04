<?php

namespace App\Actions\Admin;

use App\Mail\Admin\SslExpiredMail;
use App\Mail\Admin\SslExpiresSoonMail;
use App\Models\User;
use App\Services\Admin\CertificateService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckSslCertificate extends CertificateService
{
    /**
     * # of days before the certificate expires that a notification should be sent
     *
     * @var array
     */
    protected $notificationDays = [15, 30, 60, 90];

    /**
     * Check to see if the SSL Certificate is set to expire soon.  If so, email
     * the users with Installer Role to notify them of the upcoming expiration
     * date.
     */
    public function __invoke(): void
    {
        Log::info('Checking SSL Certificate expiration date');

        $notificationGroup = User::where('role_id', 1)->get();
        $certData = $this->buildCertMetaData();
        $expires = $certData->expirationDate();
        $daysToExpire = (int) floor(Carbon::now()->diffInDays($expires));

        // If the cert has already expired, send the expire notice
        if ($daysToExpire <= 0) {
            Mail::to($notificationGroup)->send(new SslExpiredMail);
            Log::alert('SSL Certificate has expired.  Please upload a new certificate');

            return;
        }

        if (in_array($daysToExpire, $this->notificationDays)) {
            Mail::to($notificationGroup)->send(new SslExpiresSoonMail($daysToExpire));
            Log::notice('SSL Certificate will expire in '.$daysToExpire.' days');

            return;
        }

        Log::info('SSL Certificate Valid. No action needed');
    }
}
