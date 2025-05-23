<?php

namespace App\Exceptions\Misc;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class ReportDataExpiredException extends Exception
{
    public function report(): void
    {
        Log::error('Unable to gather data for current report');
    }

    public function render(): RedirectResponse
    {
        return back()->withErrors(['page_expired' => 'Page Expired, Please Try Again']);
    }
}
