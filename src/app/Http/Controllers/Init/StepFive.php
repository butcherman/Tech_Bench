<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StepFive extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        return Inertia::render(
            'Init/StepFive',
            array_merge($request->session()->get('setup'), ['step' => 5])
        );
    }
}
