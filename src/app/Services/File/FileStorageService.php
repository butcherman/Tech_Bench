<?php

namespace App\Services\File;

use App\Exceptions\File\FileMissingException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileStorageService
{
    /**
     * Move a file from one folder to another.
     */
    public function moveDiskFile(
        string $disk,
        string $currentPath,
        string $newPath,
        ?string $newDisk = null
    ): void {
        $this->checkForDiskFile($disk, $currentPath);

        if ($newDisk) {
            $currentFullPath = Storage::disk($disk)->path($currentPath);
            $newFullPath = Storage::disk($newDisk)->path($newPath);
            $newDirPath = pathinfo($newFullPath)['dirname'];

            File::ensureDirectoryExists($newDirPath);
            File::move($currentFullPath, $newFullPath);

            return;
        }

        Storage::disk($disk)->move($currentPath, $newPath);
    }

    /**
     * Delete a file from a storage disk.
     */
    public function deleteDiskFile(string $disk, string $path): void
    {
        $this->checkForDiskFile($disk, $path);

        Storage::disk($disk)->delete($path);
    }

    protected function checkForDiskFile(string $disk, string $path): void
    {
        if (Storage::disk($disk)->missing($path)) {
            throw new FileMissingException($path);
        }
    }
}
