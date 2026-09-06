<?php

namespace App\Actions\Maintenance;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ParseLogFile
{
    /**
     * Regex pattern for standard application log entries
     *
     * @var string
     */
    protected $appLogEntryPattern = '/^\[(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})\] (?:.*?(\w+)\.)(?:.*?(\w+)\:) (.*?)? (?:\{(.*?)\})? (?:\{(.*?)\})$/i';

    /**
     * Regex pattern for log entries that are missing Context or additional data
     *
     * @var string
     */
    protected $contextMissingPattern = '/^\[(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})\] (?:.*?(\w+)\.)(?:.*?(\w+)\:) (.*)?$/i';

    public function __invoke(string $logFile)
    {
        $log = file(Storage::disk('logs')->path('Application/'.$logFile.'.log'));

        return $this->parseFileArray($log);
    }

    /**
     * Separate the sections of the log file for formatting
     */
    private function parseFileArray(array $logFileArray): array
    {
        $entries = [];

        foreach ($logFileArray as $entry) {
            $parsedEntry = $this->parseLogEntry($entry);

            if (! $parsedEntry) {
                $entries[array_key_last($entries)]['stack_trace'][] = $entries;
            } else {
                $entries[] = $parsedEntry;
            }
        }

        return $entries;
    }

    /**
     * Parse an individual Log Entry
     */
    private function parseLogEntry(string $entry): array|false
    {
        // If this is a standard entry line, we will return normal data
        if (preg_match($this->appLogEntryPattern, $entry, $data)) {

            $context = isset($data[7]) ? json_decode($data[7], true) : null;

            return [
                'time' => Carbon::parse($data[1].' '.$data[2])
                    ->setTimezone(config('app.timezone'))
                    ->format('m-d h:i A'),
                'env' => $data[3],
                'level' => Str::lower($data[4]),
                'user' => $context ? $context['user']['full_name'] : null,
                'message' => $data[5],
                'data' => $data[6] ? json_decode('{'.$data[6].'}') : null,
                'context' => $context,
            ];
        }

        // If the entry is missing context data, return normal data as well
        if (preg_match($this->contextMissingPattern, $entry, $data)) {
            return [
                'time' => Carbon::parse($data[1].' '.$data[2])
                    ->setTimezone(config('app.timezone'))
                    ->format('m-d h:i A'),
                'env' => $data[3],
                'level' => Str::lower($data[4]),
                'user' => null,
                'message' => $data[5],
                'data' => null,
                'context' => null,
            ];
        }

        return false;
    }
}
