<?php

namespace App\Jobs\File;

use App\Models\FileUpload;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteFileDataJob implements ShouldQueue
{
    use Queueable;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(protected FileUpload $fileUpload) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fileData = $this->fileUpload;

        try {
            $path = $fileData->folder . DIRECTORY_SEPARATOR . $fileData->file_name;

            $fileData->delete();
            Storage::disk($fileData->disk)->delete($path);

            Log::notice('File ' . $path . ' on disk ' . $fileData->disk . ' has been deleted');
        } catch (QueryException $e) {
            Log::notice(
                'Attempt to delete file failed, file is still in use',
                $fileData->toArray()
            );
        }
    }
}
