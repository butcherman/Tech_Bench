<?php

namespace App\Http\Controllers\Home;

use App\Facades\CacheData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Home/About', [
            'build' => fn() => CacheData::appData()['build'],
            'build_date' => fn() => CacheData::appData()['build_date'],
        ]);
    }
}
