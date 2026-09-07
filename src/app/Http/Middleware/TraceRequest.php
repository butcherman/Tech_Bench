<?php

namespace App\Http\Middleware;

use App\Services\_Base\TraceContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/*
|-------------------------------------------------------------------------------
| Add Context data to the request, including a Trace ID for debugging
|-------------------------------------------------------------------------------
*/
class TraceRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $context = app(TraceContext::class);

        $traceId = $context->set(
            $request->header('X-Trace-Id')
        );

        Context::add('trace_id', $traceId);
        Context::add(
            'user_id',
            $request->user() ? $request->user()->user_id : null
        );
        Context::add('ip_address', $request->ip());

        Log::debug('middleware is triggered');

        try {
            $response = $next($request);

            $response->headers->set(
                'X-Trace-Id',
                $traceId,
            );

            return $response;
        } finally {
            Log::withoutContext(['trace_id']);
            $context->clear();
        }
    }
}
