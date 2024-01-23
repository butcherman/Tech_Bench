<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Service\Cache;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StepOne extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Init/StepOne', [
            'rules' => Cache::PasswordRules(),
            'step' => 1,
        ]);
    }
}
