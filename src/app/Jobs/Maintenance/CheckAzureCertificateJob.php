<?php

namespace App\Jobs\Maintenance;

use App\Actions\Socialite\CheckSocialiteSecret;
use App\Mail\Admin\AzureCertificateExpiredMail;
use App\Mail\Admin\AzureCertificateExpiresSoonMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/*
|-------------------------------------------------------------------------------
| If OATH feature is enabled, the Azure Secret will expire after xx days (setup
| by system administrator).  This job will send a notification email to local
| system administrators if the Azure Secret is getting close to expiring.
|-------------------------------------------------------------------------------
*/

class CheckAzureCertificateJob implements ShouldQueue
{
    use Queueable;

    /**
     * An email notification will be sent if the days left matches any of the
     * noted array below.
     */
    protected $notifyDays = [1, 2, 3, 4, 5, 10, 15, 30, 60, 90];

    /**
     * Execute the job.
     */
    public function handle(CheckSocialiteSecret $svc): void
    {
        // If the feature is disabled, we do not need to run job
        if (! config('services.azure.allow_login')) {
            Log::debug('Check Azure Certificate Job skipped, feature disabled');

            return;
        }

        Log::debug('Starting Check Azure Certificate Job');

        $certDaysLeft = $svc();

        if ($certDaysLeft <= 0) {
            $notificationGroup = User::where('role_id', 1)->get();

            Log::alert('Azure secret is expired.  SSO Login will not work.');

            Mail::to($notificationGroup)->send(new AzureCertificateExpiredMail);
        }

        if (in_array($certDaysLeft, $this->notifyDays)) {
            $notificationGroup = User::where('role_id', 1)->get();

            Log::alert('Azure secret will expire in '.$certDaysLeft.' days.');

            Mail::to($notificationGroup)
                ->send(new AzureCertificateExpiresSoonMail($certDaysLeft));
        }

        Log::debug('Check Azure Certificate Job completed');
    }
}
