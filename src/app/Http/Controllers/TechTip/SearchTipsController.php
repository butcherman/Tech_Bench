<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchTipsController extends Controller
{
    /**
     * Perform a Tech Tip Search based on supplied parameters.
     */
    public function __invoke(Request $request)
    {
        //
        return 'search';
    }
}
