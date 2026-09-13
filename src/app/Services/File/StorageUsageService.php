<?php

namespace App\Services\File;

use App\Enums\DiskEnum;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class StorageUsageService
{
    public function getUsage(DiskEnum $disk): array
    {
        $path = Storage::disk($disk->value)->path('/');

        $total = disk_total_space($path);
        $free = disk_free_space($path);

        if ($total === false || $free === false) {
            throw new RuntimeException(
                "Unable to determine storage usage for {$path}."
            );
        }

        $used = $total - $free;

        return [
            'total' => $total,
            'used' => $used,
            'free' => $free,
            'used_percent' => round(($used / $total) * 100, 1),
        ];
    }
}
