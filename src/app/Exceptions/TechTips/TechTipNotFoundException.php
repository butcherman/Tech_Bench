<?php

namespace App\Exceptions\TechTips;

use App\Http\Middleware\HandleInertiaRequests;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class TechTipNotFoundException extends Exception
{
    public function __construct(protected Request $request)
    {
        parent::__construct();
    }

    public function report(): void
    {
        Log::warning('Unable to find requested Tech Tip page', [
            'user' => $this->request->user()->username,
            'path' => $this->request->path(),
        ]);
    }

    public function render(): Response
    {
        $middlewareData = (new HandleInertiaRequests)->share($this->request);

        return Inertia::render('TechTips/NotFound', $middlewareData);
    }
}
