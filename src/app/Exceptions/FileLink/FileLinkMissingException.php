<?php

namespace App\Exceptions\FileLink;

use App\Http\Middleware\HandleInertiaRequests;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class FileLinkMissingException extends Exception
{
    /**
     * Exception is triggered when someone tries to visit a file link that does
     * not exist.
     */
    public function __construct(protected Request $request)
    {
        parent::__construct();
    }

    public function report(): void
    {
        Log::alert('Someone is trying to visit a File Link that does not exist', [
            'ip_address' => $this->request->ip(),
        ]);
    }

    public function render(): Response
    {
        $middlewareData = (new HandleInertiaRequests)->share($this->request);

        return Inertia::render('Public/FileLinks/Missing', $middlewareData);
    }
}
