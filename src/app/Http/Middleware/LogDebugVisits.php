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
    protected $ignore = ['_token', 'token'];

    /**
     * Sensitive data is logged as received, but the values will be redacted
     *
     * @var array<int, string>
     */
    protected $redact = [
        'password',
        'password_confirmation',
        'current_password',
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

    /**
     * When Debug Logging is one, log every page visit and all $request data
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Generate unique ID and add to Context to be tagged in all logging entries
        Context::add('trace_id', Str::uuid()->toString());

        // If log level is not set to debug, continue on
        if (config('logging.channels.daily.level') === 'debug') {
            $this->logDebugVisit($request);
        }

        return $next($request);
    }

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

        $requestData = $request->all();
        $currentRoute = $request->path();

        Log::debug('Route '.$currentRoute.' visited by '.$user);

        if ($requestData) {
            // Verify we do not log any sensitive data
            foreach ($requestData as $index => $data) {
                if (in_array($data, $this->ignore)) {
                    unset($requestData[$index]);
                }

                if (in_array($data, $this->redact)) {
                    $requestData[$index] = '[REDACTED]';
                }
            }

            Log::debug('Submitted Data ', $requestData);
        }
    }
}
