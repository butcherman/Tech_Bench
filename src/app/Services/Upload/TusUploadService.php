<?php

namespace App\Services\Upload;

use ArthurPatriot\Tus\Exceptions\FileNotFoundException;
use ArthurPatriot\Tus\Facades\Tus;
use ArthurPatriot\Tus\Helpers\TusFile;
use finfo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TusUploadService
{
    /**
     * Retrieve a completed tus upload.
     */
    public function getCompleted(string $id): TusFile
    {
        try {
            $upload = TusFile::find($id);
        } catch (FileNotFoundException) {
            throw ValidationException::withMessages([
                'upload_id' => 'The uploaded file could not be found.',
            ]);
        }

        $expectedSize = (int) ($upload->metadata['size'] ?? 0);
        $actualSize = Tus::storage()->size($upload->path);

        if ($expectedSize <= 0 || $actualSize !== $expectedSize) {
            throw ValidationException::withMessages([
                'upload_id' => 'The file upload is not complete.',
            ]);
        }

        return $upload;
    }

    /**
     * Get the MIME type of the file
     */
    public function getMimeType(string $filePath): string
    {
        return (new finfo(FILEINFO_MIME_TYPE))->file($filePath);
    }

    /**
     * Validate that the file has the correct MIME type
     */
    // public function validateMimeType(string $filePath, array $allowedMimes): bool
    // {
    //     $finfo = new finfo(FILEINFO_MIME_TYPE);

    //     $mime = $finfo->file($filePath);

    //     if ($mime === false) {
    //         return false;
    //     }

    //     return in_array($mime, $allowedMimes, true);
    // }

    // public function finalize(TusFile $upload, string $destination): void
    // {
    //     rename(
    //         Tus::storage()->path($upload->path),
    //         Storage::disk('public')->path($destination)
    //     );
    // }

    /**
     * Delete a tus upload and its metadata.
     */
    public function delete(TusFile $upload): void
    {
        Tus::storage()->delete($upload->path);
        Tus::storage()->delete(Tus::path($upload->id, 'json'));
    }
}
