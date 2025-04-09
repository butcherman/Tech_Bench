<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HandleFileTrait
{
    /*
    |---------------------------------------------------------------------------
    | Sanitize the filename to remove any spaces or illegal characters.
    |---------------------------------------------------------------------------
    */
    public function cleanFilename(string $name): string
    {
        $newName = str_replace(
            ' ',
            '_',
            preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $name)
        );

        return $newName;
    }

    /*
    |---------------------------------------------------------------------------
    | Check if the filename already exists. If it does, append the filename
    | with an index number.
    |---------------------------------------------------------------------------
    */
    public function checkForDuplicate(
        string $disk,
        string $folder,
        string $name
    ): string {
        if (
            Storage::disk($disk)
                ->exists($folder.DIRECTORY_SEPARATOR.$name)
        ) {
            // Index for appending filename
            $number = 0;

            $parts = pathinfo($name);

            // File Extension
            $ext = isset($parts['extension']) ? ('.'.$parts['extension']) : '';

            // Base filename without extension or folder
            $base = preg_replace('(\(\d\))', '', $parts['filename']);

            // Append filename until it is unique
            do {
                $name = $base.'('.++$number.')'.$ext;
            } while (
                Storage::disk($disk)
                    ->exists($folder.DIRECTORY_SEPARATOR.$name)
            );
        }

        return $name;
    }

    /*
    |---------------------------------------------------------------------------
    | Simple function to convert size in bytes to readable file size
    |---------------------------------------------------------------------------
    */
    protected function readableFileSize(int $bytes, int $decimals = 2): string
    {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf(
            "%.{$decimals}f",
            $bytes / pow(1024, $factor)
        ).@$size[$factor];
    }

    public function toReadableFileSize()
    {
        return '23';
    }
}
