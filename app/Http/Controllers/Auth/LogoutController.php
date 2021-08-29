<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    /**
     * Log user out of the application
     */
    public function __invoke(Request $request)
    {
        Auth::logout();
        session()->flush();

        return redirect(route('home'));
    }
}
