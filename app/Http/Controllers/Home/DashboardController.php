<?php

namespace App\Http\Controllers\Home;

use Inertia\Inertia;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     *  Landing page for authorized users
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Home/Dashboard');
    }
}
