<?php

namespace App\Http\Controllers\Init;

use App\Actions\Maintenance\BuildApplication;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SaveSetupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        new BuildApplication($request->session()->pull('setup')); // TODO - Change back to pull

        return response()->json(['success' => true, 'url' => config('app.url')]);
    }
}
