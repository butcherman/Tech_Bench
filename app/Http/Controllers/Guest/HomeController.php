<?php

namespace App\Http\Controllers\Guest;

use Inertia\Inertia;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Home page shows login screen
     */
    public function __invoke()
    {
        return Inertia::render('Auth/login');
    }
}
