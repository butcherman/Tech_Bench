<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SaveSetupController extends Controller
{
    /**
     * Save the current Init step in the session and move onto the next step.
     */
    public function __invoke(Request $request)
    {
        return 'verify information';
    }
}
