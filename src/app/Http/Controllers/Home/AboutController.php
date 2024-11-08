<?php

namespace App\Http\Controllers\Home;

use App\Facades\CacheFacade;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    /**
     * Show the applications About Page
     */
    public function __invoke(): Response
    {
        return Inertia::render('Home/About', [
            'build' => fn () => CacheFacade::appData()['build'],
            'build_date' => fn () => CacheFacade::appData()['build_date'],
        ]);
    }
}
