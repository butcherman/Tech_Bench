<?php

namespace App\Http\Controllers\Guest;

use Inertia\Inertia;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        return Inertia::render('Auth/login');
    }
}
