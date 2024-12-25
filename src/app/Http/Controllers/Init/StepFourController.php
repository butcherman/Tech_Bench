<?php

namespace App\Http\Controllers\Init;

use App\Facades\CacheFacade;
use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StepFourController extends Controller
{
    /**
     * Step 4.  Administrative User.
     */
    public function __invoke(Request $request)
    {
        $user = $request->session()
            ->get('setup.administrator-account')
            ?: $request->user()->makeVisible(['role_id']);

        return Inertia::render('Init/StepFour', [
            'step' => 4,
            'rules' => CacheFacade::passwordRules(),
            'roles' => [UserRole::find(1)],
            'user' => $user,
            'has-pass' => $request->session()
                ->has('setup.administrator-password'),
        ]);
    }
}
