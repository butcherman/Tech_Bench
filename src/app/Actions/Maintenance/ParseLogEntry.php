<?php

namespace App\Actions\Maintenance;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ParseLogEntry
{
    public function __invoke(string $entry): ?array
    {
        $logHeaders = $this->parseLogHeaders($entry);

        if (! $logHeaders) {
            return null;
        }

        $bodyData = $this->extractBodyData(($logHeaders['body']));

        $entryData = [
            'timestamp' => Carbon::parse($logHeaders['timestamp']),
            'env' => $logHeaders['environment'],
            'level' => Str::lower($logHeaders['level']),
            'data' => $bodyData,
            'user' => $bodyData['context']['user'] ?? null,
        ];

        return $entryData;
    }

    /**
     * Get the log entry headers
     */
    private function parseLogHeaders(string $entry): array
    {
        preg_match(
            '/^\[(?<timestamp>[^\]]+)\]\s+(?<environment>[^.]+)\.(?<level>[A-Z]+):\s*(?<body>.*)$/s',
            $entry,
            $matches
        );

        return Arr::only($matches, ['timestamp', 'environment', 'level', 'body']);
    }

    /**
     * Pull the JSON data out of the message body.
     */
    private function extractBodyData(string $body)
    {
        $lines = preg_split('/\R/', $body);
        $firstLine = array_shift($lines);

        // Extract the Stack Trace from the rest of the entry
        $stackTrace = array_filter(
            $lines,
            static fn (string $line): bool => trim($line) !== ''
        );

        $jsonData = $this->extractTrailingJson($firstLine);

        $jsonData['stack_trace'] = $stackTrace;

        return $jsonData;
    }

    /**
     * Separate context and additional data from message body
     */
    private function extractTrailingJson(string $body): array
    {
        $json = [];

        while (true) {
            $position = $this->findTrailingJsonStart($body);

            if ($position === null) {
                break;
            }

            $candidate = trim(substr($body, $position));

            try {
                $decoded = json_decode(
                    $candidate,
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );
            } catch (\JsonException) {
                break;
            }

            array_unshift($json, $decoded);

            $body = trim(substr($body, 0, $position));
        }

        $json = array_reverse($json);

        if (count($json) === 2 || count($json) === 0) {
            return [
                'body' => $body,
                'context' => $json[0] ?? null,
                'extra' => $json[1] ?? null,
            ];
        }

        $isContext = array_key_exists('trace_id', $json[0]);

        if ($isContext) {
            return [
                'body' => $body,
                'context' => $json[0],
                'extra' => null,
            ];
        }

        return [
            'body' => $body,
            'context' => null,
            'extra' => $json[0],
        ];
    }

    /**
     * Find the start of the JSON string in the message body
     */
    private function findTrailingJsonStart(string $body): ?int
    {
        $body = rtrim($body);

        if ($body === '' || ! str_ends_with($body, '}')) {
            return null;
        }

        $depth = 0;
        $inString = false;
        $escaped = false;

        for ($i = strlen($body) - 1; $i >= 0; $i--) {
            $character = $body[$i];

            if ($inString) {
                if ($escaped) {
                    $escaped = false;

                    continue;
                }

                if ($character === '\\') {
                    $escaped = true;

                    continue;
                }

                if ($character === '"') {
                    $inString = false;
                }

                continue;
            }

            if ($character === '"') {
                $inString = true;

                continue;
            }

            if ($character === '}') {
                $depth++;

                continue;
            }

            if ($character === '{') {
                $depth--;

                if ($depth === 0) {
                    return $i;
                }
            }
        }

        return null;
    }
}
