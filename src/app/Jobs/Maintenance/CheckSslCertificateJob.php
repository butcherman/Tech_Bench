<?php

namespace App\Jobs\Maintenance;

use App\Mail\Admin\SslExpiredMail;
use App\Mail\Admin\SslExpiresSoonMail;
use App\Models\User;
use App\Services\Admin\CertificateService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckSslCertificateJob implements ShouldQueue
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
    public function handle(CertificateService $svc): void
    {
        Log::debug('Starting Check SSL Certificate Job');

        $certDaysLeft = $svc->getCertDaysLeft();

        if ($certDaysLeft <= 0) {
            $notificationGroup = User::where('role_id', 1)->get();

            Log::alert('SSL Certificate is expired.  Please upload a new SSL Certificate');

            Mail::to($notificationGroup)->send(new SslExpiredMail);
        }

        if (in_array($certDaysLeft, $this->notifyDays)) {
            $notificationGroup = User::where('role_id', 1)->get();

            Log::alert('SSL Certificate will expire in '.$certDaysLeft.' days.');

            Mail::to($notificationGroup)->send(new SslExpiresSoonMail($certDaysLeft));
        }
    }
}
