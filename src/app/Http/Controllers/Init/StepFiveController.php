<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StepFiveController extends Controller
{
    /**
     * Step 5.  Verify information.
     */
    public function __invoke(Request $request): Response
    {
        $summary = $request->session()->get('setup', []);
        $summary['admin']['password'] = '*****';
        $summary['admin']['password_confirmation'] = '*****';

        return Inertia::render(
            'Init/StepFive',
            array_merge($summary, ['step' => 5])
        );
    }
}
