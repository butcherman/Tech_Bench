<?php

namespace App\Exceptions\Customer;

use App\Http\Middleware\HandleInertiaRequests;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerNotFoundException extends Exception
{
    protected $request;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function report()
    {
        Log::stack(['daily', 'cust'])->warning('Unable to find request customer page', [
            'user' => $this->request->user()->username,
            'path' => $this->request->path(),
        ]);
    }

    public function render()
    {
        $middlewareData = (new HandleInertiaRequests)->share($this->request);

        return Inertia::render('Customer/NotFound', $middlewareData)
            ->toResponse($this->request)
            ->setStatusCode(404);
    }
}