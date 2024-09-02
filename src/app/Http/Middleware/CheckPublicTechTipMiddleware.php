<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPublicTechTipMiddleware
{
    /**
     * For Public Tech Tips pages.  If feature is disabled, block request
     * If tip is not public, block request
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * If Feature is disabled, abort
         */
        if (!config('techTips.allow_public')) {
            abort('404');
        }

        /**
         * If Tech Tip is not public, abort
         */
        if ($request->tech_tip && !$request->tech_tip->public) {
            abort('404');
        }

        return $next($request);
    }
}
