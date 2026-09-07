<?php

namespace App\Console\Middleware;

use App\Services\_Base\TraceContext;
use Closure;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;

class TraceConsole
{
    public function handle(
        mixed $input,
        mixed $output,
        Closure $next,
    ): int {
        $traceId = app(TraceContext::class)->set();

        Context::add([
            'trace_id' => $traceId,
        ]);

        try {
            return $next($input, $output);
        } finally {
            Log::withoutContext(['trace_id']);
            app(TraceContext::class)->clear();
        }
    }
}
