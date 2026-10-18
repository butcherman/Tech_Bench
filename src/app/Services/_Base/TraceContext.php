<?php

namespace App\Services\_Base;

use Illuminate\Support\Str;

class TraceContext
{
    private ?string $traceId = null;

    /**
     * Set the Trace ID
     */
    public function set(?string $traceId = null): string
    {
        return $this->traceId = $traceId ?: (string) Str::uuid();
    }

    /**
     * Fetch existing Trace ID or generate a new one
     */
    public function id(): string
    {
        return $this->traceId ??= (string) Str::uuid();
    }

    /**
     * Check if the Trace ID exists
     */
    public function has(): bool
    {
        return $this->traceId !== null;
    }

    /**
     * Clear the Trace ID
     */
    public function clear(): void
    {
        $this->traceId = null;
    }
}
