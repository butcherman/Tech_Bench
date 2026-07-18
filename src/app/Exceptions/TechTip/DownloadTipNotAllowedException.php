<?php

namespace App\Exceptions\TechTip;

use Exception;
use Illuminate\Support\Facades\Log;

class DownloadTipNotAllowedException extends Exception
{
    public function report(): void
    {
        Log::notice('User trying to download Tech Tip when disabled', [
            'user' => request()->user()->username,
            'path' => request()->path(),
        ]);
    }

    public function render(): void
    {
        abort(404);
    }
}
