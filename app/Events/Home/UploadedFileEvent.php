<?php

namespace App\Events\Home;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\FileUploads;

/**
 * @codeCoverageIgnore
 */
class UploadedFileEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $file;

    /**
     * Create a new event instance
     */
    public function __construct(FileUploads $file)
    {
        $this->file = $file;
    }
}
