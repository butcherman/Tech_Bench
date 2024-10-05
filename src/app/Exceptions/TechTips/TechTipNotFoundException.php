<?php

// TODO - Refactor

namespace App\Exceptions\TechTips;

use App\Http\Middleware\HandleInertiaRequests;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TechTipNotFoundException extends Exception
{
    protected $request;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function report(): void
    {
        Log::warning('Unable to find requested Tech Tip page', [
            'user' => $this->request->user()->username,
            'path' => $this->request->path(),
        ]);
    }

    public function render()
    {
        $middlewareData = (new HandleInertiaRequests)->share($this->request);

        return Inertia::render('TechTips/NotFound', $middlewareData);
    }
}
