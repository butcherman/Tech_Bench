<?php

namespace App\Jobs\Admin;

use App\Actions\Admin\CheckSslCertificate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CheckSslCertificateJob implements ShouldQueue
{
    use Queueable;

    /*
    |---------------------------------------------------------------------------
    | Check to see if the SSL Certificate is set to expire soon and email
    | installer level users if it is.
    |---------------------------------------------------------------------------
    */
    public function handle(CheckSslCertificate $svc): void
    {
        Log::debug('Starting Check SSL Certificate Job');

        $svc();

        Log::debug('Check SSL Certificate Job Complete');
    }
}
