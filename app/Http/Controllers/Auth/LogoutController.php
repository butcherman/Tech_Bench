<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Log user out of the application
     */
    public function __invoke(Request $request)
    {
        $msg = 'Successfully Logged Out';
        if($request->has('reason'))
        {
            switch($request->input('reason')) {
                case 'timeout':
                    $msg = 'You have been logged out after being idle for more than 15 minutes';
                    break;
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('home'))->withErrors($msg);
    }
}
