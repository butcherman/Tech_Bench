<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * Create an HTTP response that represents the object
     */
    public function toResponse($request)
    {
        $msg = 'Successfully Logged Out';

        if ($request->has('reason')) {
            switch ($request->input('reason')) {
                case 'timeout':
                    $msg = 'You have been logged out after being idle for more than 15 minutes';
                    break;
            }
        }

        return redirect(route('home'))->withErrors($msg);
    }
}
