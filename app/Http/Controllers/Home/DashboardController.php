<?php

namespace App\Http\Controllers\Home;

use Inertia\Inertia;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     *  Landing page for authorized users
     */
    public function __invoke(Request $request)
    {


        // dd(Auth::user()->notifications);



        return Inertia::render('Home/Dashboard', [
            'notifications' => $request->user()->notifications,
        ]);
    }
}
