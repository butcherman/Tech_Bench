<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Models\UserRole;
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
            'step' => 1,
            'rules' => Cache::PasswordRules(),
            'roles' => [UserRole::find(1)],
            'user' => $request->user()->makeVisible(['role_id']),
        ]);
    }
}
