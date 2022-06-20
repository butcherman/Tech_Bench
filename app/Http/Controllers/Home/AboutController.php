<?php

namespace App\Http\Controllers\Home;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use PragmaRX\Version\Package\Version;

class AboutController extends Controller
{
    /**
     *  About Tech Bench Page
     */
    public function __invoke()
    {
        $version = new Version;

        return Inertia::render('Home/About', [
            'version'    => $version->full(),
            'build'      => $version->commit(),
            'build_date' => $version->build(),
            'copyright'  => $version->copyright(),
        ]);
    }
}
