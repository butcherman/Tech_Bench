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
     * Validate an attempted file download to make sure the user has permission
     * to download, and that the file exists.
     */
    public function __invoke(FileUpload $fileData, string $providedName, ?User $user = null): bool
    {
        $this->verifyFileName($fileData, $providedName);
        $this->verifyPublicDownload($fileData, $user);
        $this->verifyFileExists($fileData);

        return true;
    }

    /**
     * Verify that the File Name passed to download route matches file
     */
    protected function verifyFileName(FileUpload $fileData, string $providedName): void
    {
        throw_if(
            $fileData->file_name !== $providedName,
            new IncorrectFilenameException($providedName, $fileData)
        );
    }

    /**
     * If the file is public, make sure the file is tagged for public download
     */
    protected function verifyPublicDownload(FileUpload $fileData, ?User $user = null): void
    {
        throw_if(
            is_null($user) && ! $fileData->public,
            new PrivateFileException($fileData)
        );
    }

    /**
     * Verify that the file exists on the file system
     */
    protected function verifyFileExists(FileUpload $fileData): void
    {
        throw_if(! $fileData->fileExists(), new FileMissingException($fileData));
    }
}
