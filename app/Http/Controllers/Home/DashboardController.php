<?php

namespace App\Http\Controllers\Home;

use Inertia\Inertia;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     *  Landing page for authenticated users
     */
    public function __invoke()
    {
        return Inertia::render('Auth/dashboard');
    }
}
