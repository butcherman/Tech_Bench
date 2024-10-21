<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Models\UserRole;
use App\Service\Cache;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StepFour extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $user = $request->session()
            ->get('setup.administrator-account')
            ?: $request->user()->makeVisible(['role_id']);

        return Inertia::render('Init/StepFour', [
            'step' => 4,
            'rules' => Cache::PasswordRules(),
            'roles' => [UserRole::find(1)],
            'user' => $user,
            'has-pass' => $request->session()
                ->has('setup.administrator-password'),
        ]);
    }
}
