<?php

namespace App\Jobs\Admin;

use App\Actions\Socialite\CheckSocialiteSecret;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CheckAzureCertificateJob implements ShouldQueue
{
    use Queueable;

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

        $svc();

        Log::debug('Check Azure Certificate Job completed');
    }
}
