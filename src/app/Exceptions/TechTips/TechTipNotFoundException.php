<?php

namespace App\Exceptions\TechTips;

use App\Http\Middleware\HandleInertiaRequests;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        Log::stack(['daily', 'cust'])->warning('Unable to find requested Tech Tip page', [
            'user' => $this->request->user()->username,
            'path' => $this->request->path(),
        ]);
    }

    public function render(): Response
    {
        $middlewareData = (new HandleInertiaRequests)->share($this->request);

        return Inertia::render('TechTips/NotFound', $middlewareData)
            ->toResponse($this->request)
            ->setStatusCode(404);
    }
}
