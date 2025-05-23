<?php

namespace App\Exceptions\Security;

use Exception;
use Illuminate\Support\Facades\Log;

class SslCertificateMissingException extends Exception
{
    public function report(): void
    {
        Log::error('No SSL Certificate is currently loaded into the system.');
    }
}
