<?php

// TODO - Refactor

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Models\UserRole;
use App\Service\Cache;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StepFour extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user()->makeVisible(['role_id']);

        if ($request->session()->has('setup.administrator-account')) {
            $user = $request->session()->get('setup.administrator-account');
        }

        return Inertia::render('Init/StepFour', [
            'step' => 4,
            'rules' => Cache::PasswordRules(),
            'roles' => [UserRole::find(1)],
            'user' => $user,
            'has-pass' => $request->session()->has('setup.administrator-password'),
        ]);
    }
}