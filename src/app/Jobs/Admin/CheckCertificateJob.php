<?php

namespace App\Jobs\Admin;

use App\Models\User;
use App\Notifications\Security\CertificateExpiredNotification;
use App\Notifications\Security\CertificateExpiresSoonNotification;
use App\Notifications\Security\CertificateInvalidNotification;
use App\Service\CertificateService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * This job will query the SSL Certificate and make sure that it is valid.
 * If the cert is close to expiration, a warning email will be sent to
 * all users with the Installer role at 90, 60, 30, and 15 days.
 */
class CheckCertificateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $certObj;

    protected $certData;

    protected $notifyUsers;

    protected $notifyDays = [15, 30, 60, 90];

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->certObj = new CertificateService;
        $this->certData = $this->certObj->getCertData();

        Log::info('Check Certificate Job starting');

        $this->notifyUsers = User::where('role_id', 1)->get();

        // Check if the certificate is valid or not
        if (! $this->certData) {
            Notification::send($this->notifyUsers, new CertificateInvalidNotification);
            Log::critical('SSL Certificate is invalid.  Please upload a valid SSL Certificate!!');

            return;
        }

        $certExpires = $this->certObj->getCertExpiration();
        $today = Carbon::now();
        $daysToExpire = floor($today->diffInDays($certExpires));

        if ($today->gt($certExpires)) {
            Notification::send($this->notifyUsers, new CertificateExpiredNotification);
            Log::critical('SSL Certificate has expired.  Please upload a valid SSL Certificate!!');

            return;
        }

        if (in_array($daysToExpire, $this->notifyDays)) {
            Notification::send($this->notifyUsers, new CertificateExpiresSoonNotification($daysToExpire));
            Log::warning('SSL Certificate will expire in '.$daysToExpire.' days.  Please take action before certificate is expired');

            return;
        }

        Log::info('Check Certificate Job finished.  Certificate is valid, no action needed');
    }
}
