<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogDebugVisits
{
    /**
     * These items should never be logged
     *
     * @var array<int, string>
     */
    protected $ignore = ['_token'];

    /**
     * Sensitive data is logged as received, but the values will be redacted
     *
     * @var array<int, string>
     */
    protected $redact = [
        'password',
        'password_confirmation',
        'current_password',
        'client_secret',
        'code',
    ];

    /**
     * These Route prefixes are not logged
     *
     * @var array<int, string>
     */
    protected $doNotLog = [
        'broadcasting/*',
        'administration/horizon/*',
        'administration/telescope/*',
    ];

    /*
    |---------------------------------------------------------------------------
    | Add Trace Data to each log entry Context
    |---------------------------------------------------------------------------
    */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * Add Unique ID, User, and IP Address to all log entries
         */
        Context::add('trace_id', Str::uuid()->toString());
        Context::add(
            'user_id',
            $request->user() ? $request->user()->user_id : null
        );
        Context::add('ip_address', $request->ip());

        // If log level is not set to debug, continue on
        if (config('logging.channels.daily.level') === 'debug') {
            $this->logDebugVisit($request);
        }

        return $next($request);
    }

    /*
    |---------------------------------------------------------------------------
    | If Debug Logging is turned on, add additional information about visit.
    |---------------------------------------------------------------------------
    */
    protected function logDebugVisit(Request $request): void
    {
        // Determine if we need to bypass this URL
        foreach ($this->doNotLog as $bypass) {
            if ($request->is($bypass)) {
                return;
            }
        }

        // Collect information needed for logging
        $user = $request->user()
            ? $request->user()->full_name
            : $request->ip();

        $currentRoute = $request->path();

        Log::debug('Route '.$currentRoute.' visited by '.$user);

        $requestData = $this->checkRequestArray($request->toArray());

        if ($requestData) {
            Log::debug('Submitted Data', $requestData);
        }
    }

    /*
    |---------------------------------------------------------------------------
    | Determine if we need to redact or omit any data from logging request.
    |---------------------------------------------------------------------------
    */
    protected function checkRequestArray(array $requestData)
    {
        foreach ($requestData as $key => $value) {
            // If the value is an array, process the array
            if (is_array($value)) {
                $this->checkRequestArray($value);
            }

            // Check for keys that should be ignored
            if (in_array($key, $this->ignore)) {
                unset($requestData[$key]);
            }

            // Check for keys that should be redacted
            if (in_array($key, $this->redact)) {
                $requestData[$key] = '[REDACTED]';
            }
        }

        return $requestData;
    }
}
