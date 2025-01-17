<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StepFiveController extends Controller
{
    /**
     * Step 5.  Verify information.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render(
            'Init/StepFive',
            array_merge($request->session()->get('setup'), ['step' => 5])
        );
    }
}
