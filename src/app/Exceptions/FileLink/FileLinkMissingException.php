<?php

namespace App\Exceptions\FileLink;

use Exception;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class FileLinkMissingException extends Exception
{
    public function report(): void
    {
        Log::info('Someone is trying to visit an invalid File Link URL', [
            'ip_address' => request()->ip(),
        ]);
    }

    public function render(): never
    {
        abort(404);
    }
}
