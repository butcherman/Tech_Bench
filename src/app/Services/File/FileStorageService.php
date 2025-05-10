<?php

namespace App\Services\File;

use App\Exceptions\File\FileMissingException;
use Illuminate\Support\Facades\Storage;

class FileStorageService
{
    /**
     * Delete a file from a storage disk
     */
    public function deleteDiskFile(string $disk, string $path): void
    {
        if (Storage::disk($disk)->missing($path)) {
            throw new FileMissingException($path);
        }

        Storage::disk($disk)->delete($path);
    }
}
