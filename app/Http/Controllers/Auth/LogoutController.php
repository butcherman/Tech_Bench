<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     *  Log user out of Application
     */
    public function __invoke(Request $request)
    {
        Auth::logout();
        session()->flush();

        return redirect(route('home'));
    }
}
