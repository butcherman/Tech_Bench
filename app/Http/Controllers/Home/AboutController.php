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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $version = new Version;

        return Inertia::render('Home/about', [
            'version' => $version->full(),
            'build' => $version->commit(),
            'build_date' => $version->build(),
        ]);
    }
}
