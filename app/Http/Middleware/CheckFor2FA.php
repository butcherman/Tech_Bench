<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFor2FA
{
    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(session()->missing('2fa_verified')) {
            $request->user()->generateVerificationCode();

            return redirect(route('2fa.index'));
        }

        return $next($request);
    }
}
