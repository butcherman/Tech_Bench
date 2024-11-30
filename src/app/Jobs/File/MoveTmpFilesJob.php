<?php

namespace App\Jobs\File;

use App\Models\FileUpload;
use App\Services\File\HandleFileService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class MoveTmpFilesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $fileIdList,
        protected string $newFolder,
        protected ?bool $isPublic = false
    ) {}

    /**
     * Execute the job.
     */
    public function handle(HandleFileService $svc): void
    {
        $fileList = FileUpload::find($this->fileIdList);

        foreach ($fileList as $uploadedFile) {
            $svc->moveFile($uploadedFile, $this->newFolder);
            $svc->setPublicFlag($uploadedFile, $this->isPublic);
        }
    }
}
