<?php

namespace App\Actions\Misc;

use Spatie\SimpleExcel\SimpleExcelWriter;

class CsvWriter
{
    /**
     * Wrapper for the Simple Excel Writer by Spatie
     *
     * @codeCoverageIgnore
     */
    public function stream(string $fileName): SimpleExcelWriter
    {
        return SimpleExcelWriter::streamDownload($fileName);
    }
}
