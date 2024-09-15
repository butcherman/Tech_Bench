<?php

namespace App\Exceptions\FileLink;

use App\Http\Middleware\HandleInertiaRequests;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class FileLinkMissingException extends Exception
{
    protected $request;

    protected $link;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function report()
    {
        Log::alert('Someone is trying to visit a File Link that does not exist', [
            'ip_address' => $this->request->ip(),
        ]);
    }

    public function render()
    {
        $middlewareData = (new HandleInertiaRequests)->share($this->request);

        return Inertia::render('Public/FileLinks/Missing', $middlewareData);
    }
}
