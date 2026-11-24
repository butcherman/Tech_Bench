<?php

namespace App\Http\Controllers\Init;

use App\Actions\Init\BuildApplication;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaveSetupController extends Controller
{
    /**
     * Save the application settings and reboot the Tech Bench.
     */
    public function __invoke(Request $request, BuildApplication $init): JsonResponse
    {
        $init($request->session()->get('setup'));

        return response()->json([
            'success' => true,
            'url' => config('app.url'),
        ]);
    }
}
