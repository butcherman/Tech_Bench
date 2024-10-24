<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * When a user logs out, we generate a message to send along when redirecting
     * back to the login page that will show on login screen
     *
     * @param  Illuminate\Http\Request  $request
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

        return redirect(route('login'))->with('info', $msg);
    }
}
