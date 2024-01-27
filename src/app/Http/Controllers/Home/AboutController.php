<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PragmaRX\Version\Package\Version;

class AboutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $version = new Version;

        return Inertia::render('Home/About', [
            'build' => $version->commit(),
            'build_date' => $version->build(),
        ]);
    }
}
