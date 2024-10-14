<?php

// TODO - Refactor

namespace App\Http\Controllers\Init;

use App\Actions\Maintenance\BuildApplication;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaveSetupController extends Controller
{
    /**
     * Save all settings from the Setup Wizard
     */
    public function __invoke(Request $request): JsonResponse
    {
        $init = new BuildApplication($request->session()->pull('setup'));
        $init();

        return response()->json(['success' => true, 'url' => config('app.url')]);
    }
}
