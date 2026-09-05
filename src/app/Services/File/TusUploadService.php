<?php

namespace App\Services\File;

use ArthurPatriot\Tus\Exceptions\FileNotFoundException;
use ArthurPatriot\Tus\Facades\Tus;
use ArthurPatriot\Tus\Helpers\TusFile;
use finfo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TusUploadService
{
    /**
     * Retrieve a completed tus upload.
     */
    public function getCompletedUpload(string $id): TusFile
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
     * Validate that the file has the correct MIME type
     */
    public function validateMimeType(TusFile $tusFile, array $allowedMimes): bool
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $path = Tus::storage()->path($tusFile->path);

        $mime = $finfo->file($path);

        if ($mime === false) {
            return false;
        }

        Log::debug('Validating Tus Upload Mime', [
            'found' => $mime,
            'allowed' => $allowedMimes,
            'result' => in_array($mime, $allowedMimes, true),
        ]);

        return in_array($mime, $allowedMimes, true);
    }

    /**
     * Save the file to its final destination
     */
    public function finalizeUpload(TusFile $upload, string $destination): void
    {
        rename(
            Tus::storage()->path($upload->path),
            Storage::disk('public')->path($destination)
        );

        // Delete the meta data from the file
        $this->deleteUpload($upload);
    }

    /**
     * Delete a tus upload and its metadata.
     */
    public function deleteUpload(TusFile $upload): void
    {
        Tus::storage()->delete($upload->path);
        Tus::storage()->delete(Tus::path($upload->id, 'json'));
    }
}
