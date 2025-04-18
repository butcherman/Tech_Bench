<?php

// TODO - Refactor

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * When Debug Logging is turned on, all page visits will be logged along with
 * all data passed
 */
class LogDebutVisits
{
    /**
     * These items should never be logged
     */
    protected $ignore = ['_token', 'token'];

    /**
     * Sensitive data is logged as received, but the values will be redacted
     */
    protected $redact = ['password', 'password_confirmation', 'current_password'];

    /**
     * These Route prefixes are not logged
     */
    protected $doNotLog = ['broadcasting/*', 'administration/horizon/*'];    // ['horizon', 'telescope'];

    /**
     * When Debug Logging is one, log every page visit and all $request data
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Generate unique ID and add to Context to be tagged in all logging entries
        Context::add('trace_id', Str::uuid()->toString());

        // If log level is not set to debug, continue on
        if (config('logging.channels.daily.level') === 'debug') {
            // Determine if we need to bypass this URL
            foreach ($this->doNotLog as $bypass) {
                if ($request->is($bypass)) {
                    return $next($request);
                }
            }

            // Collect information needed for logging
            $user = $request->user()
                ? $request->user()->full_name
                : $request->ip();
            $requestData = $request->all();
            $currentRoute = $request->path();

            Log::debug('Route '.$currentRoute.' visited by '.$user);

            if ($requestData) {
                foreach ($this->redact as $i) {
                    if (Arr::exists($requestData, $i)) {
                        $requestData[$i] = '[REDACTED]';
                    }
                }
                foreach ($this->ignore as $i) {
                    if (Arr::exists($requestData, $i)) {
                        unset($requestData[$i]);
                    }
                }

                Log::debug('Submitted Data ', $requestData);
            }
        }

        return $next($request);
    }
}
