<?php

namespace App\Http\Controllers\Init;

use App\Actions\BuildPasswordRules;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

/**
 * First time setup - Step 1 - Change Administrators Password
 */
class StepOne extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Init/StepOne', [
            'stepId' => 1,
            'rules' => Cache::get('passwordRules', (new BuildPasswordRules)->build()),
        ]);
    }
}
