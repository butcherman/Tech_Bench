<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PragmaRX\Version\Package\Version;

/**
 * About page shows current version information
 */
class AboutController extends Controller
{
    public function __invoke(Request $request)
    {
        $version = new Version;

        return Inertia::render('Home/About', [
            'version' => $version->full(),
            'build' => $version->commit(),
            'build_date' => $version->build(),
            'copyright' => $version->copyright(),
        ]);
    }
}
