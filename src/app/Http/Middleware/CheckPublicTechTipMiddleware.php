<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPublicTechTipMiddleware
{
    /**
     * For Public Tech Tips pages.  If the feature is disabled, or the loaded
     * Tech Tip is not marked as public, block the request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * If Feature is disabled, abort
         */
        if (! config('tech-tips.allow_public')) {
            abort('404');
        }

        /**
         * If Tech Tip is not public, abort
         */
        if ($request->tech_tip && ! $request->tech_tip->public) {
            abort('404');
        }

        return $next($request);
    }
}