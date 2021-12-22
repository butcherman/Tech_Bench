<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Auth\Events\Lockout;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Auth\Events\Failed;

class LoginController extends Controller
{
    protected $loginAttempts = 4;
    protected $lockoutTimer  = 10;

    public function __construct()
    {
        //  To help prevent bots, we will not allow more than 50 login attempts within a two hour period
        $this->middleware('throttle:50,120');
    }

    /**
     *  Attempt to log a user in
     */
    public function __invoke(LoginRequest $request)
    {
        //  Determine if the user has tried to log in too many times already
        if(session('failed_login') > $this->loginAttempts)
        {
            $timeout = session('timeout') ? session('timeout') : Carbon::now()->addMinutes($this->lockoutTimer);
            if($timeout > Carbon::now())
            {
                session([
                    'timeout' => $timeout,
                ]);
                $request->session()->increment('failed_login');
                event(new Lockout($request));

                return back()->withErrors([
                    'username' => 'You have attempted to log in too many times.  Please wait '.$this->lockoutTimer.' minutes before trying again.'
                ]);
            }

            //  If the user has passed the 10 minute timeout, they can attempt a login again
            $request->session()->forget(['failed_login', 'timeout']);
        }

        $user = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        //  Successful authentication re-routes to the dashboard, or the page that the user tried to visit
        if(Auth::attempt($user, $request->remember))
        {
            $request->session()->forget(['failed_login', 'timeout']);
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        $request->session()->increment('failed_login');
        event(new Failed(null, null, $user));
        return back()->withErrors(['username' => 'Your username or password does not match our records']);
    }
}
