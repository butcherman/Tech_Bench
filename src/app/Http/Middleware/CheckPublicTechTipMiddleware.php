<?php

namespace App\Http\Middleware;

use App\Exceptions\TechTip\PublicTipsDisabledException;
use App\Exceptions\TechTip\TechTipNotPublicException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/*
|-------------------------------------------------------------------------------
| For Public Tech Tips pages.  If the feature is disabled, or the loaded Tech
| Tip is not marked as public, block the request.
|-------------------------------------------------------------------------------
*/

class CheckPublicTechTipMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * If Feature is disabled, abort
         */
        if (! config('tech-tips.allow_public')) {
            throw new PublicTipsDisabledException;
        }

        /**
         * If Tech Tip is not public, abort
         */
        if ($request->tech_tip && ! $request->tech_tip->public) {
            throw new TechTipNotPublicException;
        }

        return $next($request);
    }
}
