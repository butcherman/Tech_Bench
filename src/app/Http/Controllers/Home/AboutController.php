<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use PragmaRX\Version\Package\Version;

class AboutController extends Controller
{
    public function __construct(protected Version $version) {}

    /**
     * Show the About page with the current version
     */
    public function __invoke(): Response
    {
        return Inertia::render('Home/About', [
            'build' => $this->version->commit(),
            'build_date' => $this->version->build(),
        ]);
    }
}
