<?php

namespace App\Services\Maintenance;

use Generator;
use RuntimeException;

class ReverseLogReader
{
    private const CHUNK_SIZE = 8192;

    /**
     * Yield complete log entries from newest to oldest.
     */
    public function entries(string $path): Generator
    {
        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw new RuntimeException(
                "Unable to open log file: {$path}"
            );
        }

        try {
            $buffer = '';
            $currentEntry = [];

            fseek($handle, 0, SEEK_END);

            $position = ftell($handle);

            while ($position > 0) {
                $readSize = min(
                    self::CHUNK_SIZE,
                    $position
                );

                $position -= $readSize;

                fseek($handle, $position);

                $chunk = fread($handle, $readSize);

                if ($chunk === false) {
                    break;
                }

                $buffer = $chunk.$buffer;

                while (($newline = strrpos($buffer, "\n")) !== false) {
                    $line = substr(
                        $buffer,
                        $newline + 1
                    );

                    $buffer = substr(
                        $buffer,
                        0,
                        $newline
                    );

                    $line = rtrim($line, "\r");

                    if ($this->isEntryStart($line)) {
                        $currentEntry[] = $line;

                        yield implode(
                            "\n",
                            array_reverse($currentEntry)
                        );

                        $currentEntry = [];

                        continue;
                    }

                    $currentEntry[] = $line;
                }
            }

            /*
             * There may be one final partial line at the beginning
             * of the file. This is normally the oldest log header.
             */
            if ($buffer !== '') {
                $currentEntry[] = rtrim($buffer, "\r");
            }

            if ($currentEntry !== []) {
                yield implode(
                    "\n",
                    array_reverse($currentEntry)
                );
            }
        } finally {
            fclose($handle);
        }
    }

    private function isEntryStart(string $line): bool
    {
        return preg_match(
            '/^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]/',
            $line
        ) === 1;
    }
}
