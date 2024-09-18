<?php

namespace App\Jobs\Admin;

use App\Models\User;
use App\Notifications\Security\AzureCertificateExpiredNotification;
use App\Notifications\Security\AzureCertificateExpiresSoonNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CheckAzureCertificateJob // implements ShouldQueue
{
    use Queueable;

    protected $notifyDays = [15, 30, 60, 90];

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (! config('services.azure.allow_login')) {
            Log::debug('Check Azure Certificate Job stopped, feature disabled');

            return;
        }

        Log::info('Check Azure Certificate Job Starting');

        $expires = Carbon::parse(config('services.azure.secret_expires'));
        $today = Carbon::now();
        $daysToExpire = floor($today->diffInDays($expires));
        $notifyUsers = User::where('role_id', 1)->get();

        if ($today->gt($expires)) {
            Notification::send($notifyUsers, new AzureCertificateExpiredNotification);
            Log::critical('Azure Client Secret Expired.  Please generate a new client secret for Single Sign On to continue working');

            return;
        }

        if (in_array($daysToExpire, $this->notifyDays)) {
            Notification::send($notifyUsers, new AzureCertificateExpiresSoonNotification($daysToExpire));
            Log::warning('Azure Client Secret Expires in '.$daysToExpire.' days.  Please generate a new client secret to avoid interruptions in Single Sign On');

            return;
        }

        Log::info('Check Azure Certificate Job Finished.  Certificate is valid, no action needed');
    }
}
