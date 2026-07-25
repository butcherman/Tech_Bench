<?php

namespace App\Http\Middleware;

use App\Enums\FlashLevels;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

/*
|-------------------------------------------------------------------------------
| Pull all flash data and pass it into the Inertia::flash object
|-------------------------------------------------------------------------------
*/

class HandleFlashDataMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $flashData = session('_flash');
        if ($flashData) {
            foreach ($flashData['new'] as $flash) {
                if (FlashLevels::tryFrom($flash)) {
                    Inertia::flash($flash);
                }
            }
            foreach ($flashData['old'] as $flash) {
                if (FlashLevels::tryFrom($flash)) {
                    Inertia::flash('banner', [
                        'level' => $flash,
                        'message' => $request->session()->get($flash),
                    ]);
                }
            }
        }

        return $next($request);
    }
}
