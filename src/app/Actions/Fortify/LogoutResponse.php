<?php

namespace App\Actions\Fortify;

use Inertia\Inertia;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Symfony\Component\HttpFoundation\Response;

/*
|---------------------------------------------------------------------------
| When a user logs out, we generate a message to send along when redirecting back to the login page that will show on login screen
|---------------------------------------------------------------------------
*/

class LogoutResponse implements LogoutResponseContract
{
    /**
     * @param  \Illuminate\Http\Request  $request
     */
    public function toResponse($request): Response
    {
        $msg = 'Successfully Logged Out';

        if ($request->has('reason')) {
            switch ($request->input('reason')) {
                case 'timeout':
                    $msg = 'You have been logged out after being idle for more than '.
                        config('auth.auto_logout_timer').' minutes';
                    break;
            }
        }

        // Clear Encrypted History
        Inertia::clearHistory();

        return redirect(route('login'))->with('info', $msg);
    }
}
