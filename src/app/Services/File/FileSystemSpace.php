<?php

namespace App\Services\File;

/**
 * @codeCoverageIgnore
 */
class FileSystemSpace
{
    /**
     * Get the disk total space
     */
    public function getDiskTotalSpace(string $path): int|false
    {
        return disk_total_space($path);
    }

    /**
     * Get the disk total free space
     */
    public function getDiskFreeSpace(string $path): int|false
    {
        return disk_free_space($path);
    }
}
