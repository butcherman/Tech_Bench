<?php

namespace App\Actions\Misc;

use App\Exceptions\File\FileMissingException;
use App\Exceptions\File\IncorrectFilenameException;
use App\Exceptions\File\PrivateFileException;
use App\Models\FileUpload;
use App\Models\User;

class ValidateFileForDownload
{
    /**
     * Validate that a file can exists and can be downloaded by the user
     */
    public function handle(
        FileUpload $fileData,
        string $providedName,
        ?User $user = null
    ): bool {
        $this->verifyFileName($fileData, $providedName);
        $this->verifyPublicDownload($fileData, $user);
        $this->verifyFileExists($fileData);

        return true;
    }

    /**
     * Verify that the File Name passed to download route matches file
     */
    protected function verifyFileName(
        FileUpload $fileData,
        string $providedName
    ): void {
        if ($fileData->file_name !== $providedName) {
            throw new IncorrectFilenameException($providedName, $fileData);
        }
    }

    /**
     * If the file is public, make sure the file is tagged for public download
     */
    protected function verifyPublicDownload(
        FileUpload $fileData,
        ?User $user = null
    ): void {
        if (is_null($user) && ! $fileData->public) {
            throw new PrivateFileException($fileData);
        }
    }

    /**
     * Verify that the file exists on the file system
     */
    protected function verifyFileExists(FileUpload $fileData): void
    {
        if (! $fileData->fileExists()) {
            throw new FileMissingException($fileData);
        }
    }
}
