<?php

namespace App\Http\Controllers\Guest;

use Inertia\Inertia;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Home page shows login screen
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Auth/login');
    }
}
