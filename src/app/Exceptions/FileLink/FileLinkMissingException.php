<?php

namespace App\Exceptions\FileLink;

use App\Actions\Misc\BuildNavBar;
use App\Http\Middleware\HandleInertiaRequests;
use Exception;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class FileLinkMissingException extends Exception
{
    /**
     * Exception is triggered when someone tries to visit a file link that does
     * not exist.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function report(): void
    {
        Log::alert('Someone is trying to visit a File Link that does not exist', [
            'ip_address' => request()->ip(),
        ]);
    }

    public function render(): Response
    {
        $middlewareData = (new HandleInertiaRequests(new BuildNavBar))
            ->share(request());

        return Inertia::render('Public/FileLinks/Missing', $middlewareData);
    }
}
